<?php

namespace App\Http\Controllers;

use App\Mail\PaymentReceiptMail;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class advancePaymentController extends Controller
{

    public function advancePayment(Request $request)
    {
        $users = User::all();
        $subscription = Subscription::find($request->query('id'));

        if (!$subscription) {
            return redirect()->back()->with('error', 'Subscription not found.');
        }
        return view('advancePayment', compact('subscription', 'users'));
    }

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

            $subscriptionId = Session::get('subscription_id') ?? $request->input('subscription_id');

            if (!$subscriptionId) {
                return redirect()->route('payment')->with('error', 'Subscription ID is missing.');
            }

            $amount = $response['purchase_units'][0]['payments']['captures'][0]['amount']['value'];
            $referenceNumber = $response['id'];

            
            $currentMonth = now()->format('Y-m');
            $nextMonth = now()->addMonth()->format('Y-m');

           
            $subscription = Subscription::find($subscriptionId);
            $monthlyPrice = $subscription->price ?? 0;

           
            $monthsToPay = floor($amount / $monthlyPrice);
            $remainingAmount = $amount; 

            
            for ($i = 0; $i < $monthsToPay; $i++) {
                $paymentMonth = now()->addMonths($i)->format('Y-m');

              
                Payment::create([
                    'user_id' => $user->id,
                    'subscriptions_id' => $subscriptionId,
                    'amount' => $monthlyPrice,
                    'reference_number' => $referenceNumber,
                    'payment_month' => $paymentMonth,
                ]);

            
                $remainingAmount -= $monthlyPrice;
            }

            Mail::to($user->email)->send(new PaymentReceiptMail($user));

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

    public function changeReceipt()
    {
        return view('emails.change_receipt');
    }
}
