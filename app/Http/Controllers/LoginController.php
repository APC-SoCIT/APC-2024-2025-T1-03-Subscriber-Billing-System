<?php

namespace App\Http\Controllers;

use App\Http\Middleware\CustomerAuth;
use App\Models\Credential;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LoginController extends Controller
{
    public function index()
    {
        return view('login');
    }

    public function guestHome()
    {
        return view('guestHome');
    }

    public function accountPage()
    {
        $users = User::with('credential')->get();
        return view('accountPage', compact('users'));
    }

    public function accountPagePost(Request $request)
    {
        $userId = $request->input('userId');

        $user = User::findOrFail($userId);
        $user->email = $request->email;
        $user->mobileNo = $request->mobileNo;
        $user->birthday = $request->bday;
        $user->streetAddr = $request->sAddr;
        $user->city = $request->city;
        $user->postal = $request->postal;
        $user->save();

        if ($request->filled('password')) {
            $credential = Credential::firstOrNew(['user_id' => $userId]);
            $credential->password = bcrypt($request->password);
            $credential->save();
        }

        return redirect()->route('accountPage')->with('success', 'Account Successfully Updated!');
    }


    public function advancePayment(Request $request)
    {
        $users = User::all();
        $subscription = Subscription::find($request->query('id'));

        if (!$subscription) {
            return redirect()->back()->with('error', 'Subscription not found.');
        }
        return view('advancePayment', compact('subscription', 'users'));
    }

    public function changeSub()
    {
        $subscriptions = Subscription::with('details')->get();
        $user = Session::get('user');

        if (!$user) {
            return redirect()->route('login')->with('error', 'Please log in to change your subscription.');
        }

        $payment = Payment::where('user_id', $user->id)->latest()->first();

        return view('changePlan', compact('subscriptions', 'payment'));
    }


    public function home()
    {

        $subscriptions = Subscription::with('details')->get();
        $user = Session::get('user'); 

        if (!$user) {
            return redirect()->route('login')->with('error', 'Session expired. Please log in again.');
        }

        $payment = Payment::where('user_id', $user->id)->with('subscription')->latest()->first();

        return view('home', compact('payment', 'subscriptions'));
    }

    public function account()
    {
        $user = Session::get('user');

        if (!$user) {
            return redirect()->route('login')->with('error', 'Please log in to view your account statement.');
        }

        // Fetch all payments
        $payments = Payment::where('user_id', $user->id)
            ->with('subscription')
            ->orderByDesc('created_at')
            ->get();

        // Default values
        $totalBilling = 0;
        $totalPayments = 0;
        $balance = 0;
        $advanceBalance = 0;
        $subscriptionPrice = 0;

        if ($payments->isNotEmpty()) {
            $subscriptionPrice = $payments->first()->subscription->price ?? 0;
        }

        foreach ($payments as $payment) {
            $billing = $payment->subscription->price ?? 0;
            $paymentAmount = $payment->amount;

            // Add to totals
            $totalBilling += $billing;
            $totalPayments += $paymentAmount;
        }

        // calcu ung months covered
        $monthsCovered = ($subscriptionPrice > 0) ? floor($totalPayments / $subscriptionPrice) : 0;
        $remainingBalance = ($subscriptionPrice > 0) ? $totalPayments % $subscriptionPrice : 0;

        // calculation ng bal
        $balance = ($remainingBalance > 0) ? 0 : ($subscriptionPrice - $totalPayments);

        // adv balance calc
        $advanceBalance = ($monthsCovered >= 1) ? 0 : $subscriptionPrice;

        if ($monthsCovered >= 1) {
            $nextDueDate = now()->addMonths($monthsCovered); 
        } else {
            $nextDueDate = now()->addMonth(); 
        }

        $latestPayment = $payments->first();
        $currentSubscription = $latestPayment?->subscription;
        $subscriptionPrice = $currentSubscription?->price ?? 0;
        $subscriptionId = $currentSubscription?->id ?? null;

        return view('account', compact(
            'payments',
            'totalBilling',
            'totalPayments',
            'balance',
            'advanceBalance',
            'nextDueDate',
            'subscriptionPrice',
            'subscriptionId'
        ));
    }




    public function payment(Request $request)
    {
        $users = User::all();
        $subscription = Subscription::find($request->query('id'));

        if (!$subscription) {
            return redirect()->back()->with('error', 'Subscription not found.');
        }

        return view('payment', compact('subscription', 'users'));
    }

    public function subscriptions()
    {
        $subscriptions = Subscription::with('details')->get();
        return view('subscription', compact('subscriptions'));
    }

    public function adminHome()
    {
        $users = User::all();
        $subscriptions = Subscription::with('details')->get();
        return view('admin.adminHome', compact('users', 'subscriptions'));
    }

    public function loginPost(Request $request)
    {
        $middleware = new CustomerAuth();

        $response = $middleware->handle($request, function ($request) {
            return redirect()->route('home');
        });

        $user = $request->session()->get('user');

        if ($user && $user->user_type == 'admin') {
            return redirect()->route('admin.home');
        }

        return $response;
    }

    public function logout(Request $request)
    {
        if (Session::has('loginId')) {
            $request->session()->flush();
            Auth::logout();  
            $request->session()->invalidate();  
            $request->session()->regenerateToken(); 

            redirect()->route('guestHome');
        }

        return redirect()->route('guestHome');
    }

    public function adminLogout(Request $request)
    {
        if (Session::has('loginId')) {
            $request->session()->flush();
            Auth::logout();  
            $request->session()->invalidate();  
            $request->session()->regenerateToken();  

            redirect()->route('guestHome');
        }

        return redirect()->route('guestHome');
    }

    public function downloadStatement()
    {
        $user = Session::get('user');
        if (!$user) {
            return redirect()->route('login')->with('error', 'Please log in to download your statement.');
        }

        $payments = Payment::where('user_id', $user->id)->with('subscription')->get();

        $response = new StreamedResponse(function () use ($payments) {
            $handle = fopen('php://output', 'w');


            fputcsv($handle, ['Date', 'Account Number', 'Plan', 'Billing', 'Payments', 'Balance']);

            $totalBilling = 0;
            $totalPayments = 0;

            foreach ($payments as $payment) {
                $billing = $payment->subscription->price ?? 0;
                $paymentAmount = $payment->amount;
                $totalBilling += $billing;
                $totalPayments += $paymentAmount;
                $currentBalance = $totalBilling - $totalPayments;

                fputcsv($handle, [
                    $payment->created_at->format('Y-m-d'),
                    $payment->user_id,
                    $payment->subscription->subscription ?? 'N/A',
                    number_format($billing, 2),
                    number_format($paymentAmount, 2),
                    number_format($currentBalance, 2)
                ]);
            }

            fputcsv($handle, ['', '', 'TOTAL', number_format($totalBilling, 2), number_format($totalPayments, 2), number_format($totalBilling - $totalPayments, 2)]);

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="account_statement.csv"');

        return $response;
    }

    public function changePlanPayment(Request $request)
    {

        $users = User::all();
        $subscription = Subscription::find($request->query('id'));

        if (!$subscription) {
            return redirect()->back()->with('error', 'Subscription not found.');
        }
        return view('changePlanPayment', compact('subscription', 'users'));
    }
}
