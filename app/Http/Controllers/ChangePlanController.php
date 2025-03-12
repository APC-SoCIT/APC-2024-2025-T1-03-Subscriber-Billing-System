<?php

namespace App\Http\Controllers;

use App\Mail\PaymentReceiptMail;
use App\Mail\PaymentReceiptMailChange;
use App\Models\Payment;
use Illuminate\Http\Request;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Mail;

class ChangePlanController extends Controller
{
    public function createPayment(Request $request)
    {
        // Save subscription_id in the session
        Session::put('subscription_id', $request->input('subscription_id'));

        $paypal = new PayPalClient;
        $paypal->setApiCredentials(config('paypal'));
        $paypal->getAccessToken();

        $order = $paypal->createOrder([
            "intent" => "CAPTURE",
            "purchase_units" => [[
                "amount" => [
                    "currency_code" => "PHP",
                    "value" => $request->amount
                ]
            ]],
            "application_context" => [
                "cancel_url" => route('paypal.cancel'),
                "return_url" => route('paypal.success')
            ]
        ]);

        if (isset($order['id']) && $order['status'] == 'CREATED') {
            foreach ($order['links'] as $link) {
                if ($link['rel'] === 'approve') {
                    return redirect()->away($link['href']);
                }
            }
        }

        return redirect()->route('payment')->with('error', 'Something went wrong.');
    }


    public function success(Request $request)
    {
        $paypal = new PayPalClient;
        $paypal->setApiCredentials(config('paypal'));
        $paypal->getAccessToken();

        $response = $paypal->capturePaymentOrder($request->token);

        if (isset($response['status']) && $response['status'] == 'COMPLETED') {

            $user = Session::get('user');

            if (!$user) {
                return redirect()->route('payment')->with('error', 'User session expired. Please log in again.');
            }

            $subscriptionId = $request->input('subscription_id') ?? Session::get('subscription_id');

            if (!$subscriptionId) {
                return redirect()->route('payment')->with('error', 'Subscription ID is missing.');
            }

            //Soft deleete
            $existingPayments = Payment::where('user_id', $user->id)->get();
            foreach ($existingPayments as $payment) {
                $payment->delete(); 
            }
            Payment::where('user_id', $user->id)->update(['deleted_at' => now()]);

            Payment::create([
                'user_id' => $user->id,
                'subscriptions_id' => $subscriptionId,
                'amount' => $response['purchase_units'][0]['payments']['captures'][0]['amount']['value'],
                'reference_number' => $response['id'],
            ]);

            Mail::to($user->email)->send(new PaymentReceiptMailChange($payment));

            return redirect()->route('home')->with('success', 'Payment successful!');
        }

        return redirect()->route('payment')->with('error', 'Payment failed.');
    }



    public function receipt()
    {
        return view('emails.receipt');
    }

    public function changeReceipt()
    {
        return view('emails.change_receipt');
    }
}
