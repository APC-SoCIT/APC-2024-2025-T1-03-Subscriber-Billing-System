<?php

namespace App\Http\Controllers;

use App\Mail\PaymentReceiptMail;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\User;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class PayPalController extends Controller
{
    public function createPayment(Request $request)
    {
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

            // Get user from session
            $user = Session::get('user');

            if (!$user) {
                return redirect()->route('payment')->with('error', 'User session expired. Please log in again.');
            }

            // Retrieve subscription_id from session if not present in the request
            $subscriptionId = $request->input('subscription_id') ?? Session::get('subscription_id');

            if (!$subscriptionId) {
                return redirect()->route('payment')->with('error', 'Subscription ID is missing.');
            }

            // Extract payment details
            $amount = $response['purchase_units'][0]['payments']['captures'][0]['amount']['value'];
            $referenceNumber = $response['id'];

            // Save payment record
            $payment = Payment::create([
                'user_id' => $user->id,
                'subscriptions_id' => $subscriptionId,
                'amount' => $amount,
                'reference_number' => $referenceNumber,
            ]);

            Mail::to($user->email)->send(new PaymentReceiptMail($payment));

            $subscription = Subscription::find($subscriptionId);

            if (!$subscription) {
                return redirect()->route('payment')->with('error', 'Subscription not found.');
            }

            return view('receipt', [
                'account_id' => $user->id,
                'plan' => $subscription,
                'account_name' => $user->firstName . ' ' . $user->lastName,
                'reference_number' => $referenceNumber,
                'date' => now()->format('F d, Y'),
                'amount' => "PHP " . number_format($amount, 2),
            ]);
        }

        return redirect()->route('payment')->with('error', 'Payment failed.');
    }


    public function cancel()
    {
        return redirect()->route('payment')->with('error', 'Payment was cancelled.');
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
