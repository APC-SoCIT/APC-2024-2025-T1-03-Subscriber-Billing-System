<?php
//ROMAN
namespace App\Http\Controllers;

use App\Models\Credential;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\SubscriptionDetails;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{

    public function adminUserList($id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()->route('adminDashboard')->with('error', 'User not found.');
        }

        $subscription = Subscription::find($id);

        // Fetch the latest payment (for the selected plan)
        $latestPayment = Payment::where('user_id', $user->id)
            ->with('subscription') // Ensure subscription details are loaded
            ->latest() // Get the most recent one
            ->first();

        // Fetch all payments (for the table)
        $payments = Payment::withTrashed() // Include soft-deleted records
            ->where('user_id', $user->id)
            ->with('subscription')
            ->latest()
            ->get();


        $totalBilling = $payments->sum(fn($payment) => $payment->subscription->price ?? 0);
        $totalPayments = $payments->sum('amount');

        // Calculate balance
        $balance = $totalBilling - $totalPayments;

        return view('admin.adminUserList', compact('user', 'latestPayment', 'payments', 'totalBilling', 'totalPayments', 'balance', 'subscription'));
    }



    public function adminSubscription()
    {
        $users = User::all();
        $subscriptions = Subscription::with('details')->get();
        return view('admin.adminSubscriptions', compact('users', 'subscriptions'));
    }

    public function adminUser()
    {
        $users = User::with('credential')
            ->orderByRaw("CASE WHEN user_type = 'Admin' THEN 0 ELSE 1 END")
            ->get();

        $subscriptions = Subscription::with('details')->get();

        return view('admin.adminUsers', compact('users', 'subscriptions'));
    }




    public function addPost(Request $request)
    {
        // Validate input fields
        $request->validate([
            'fName' => 'required|string|max:255',
            'lName' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'mobileNo' => 'required|string|unique:users,mobileNo',
            'gender' => 'required|in:Male,Female,Other',
            'bday' => 'required|date',
            'sAddr' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'postal' => 'required|string|max:10',
        ]);

        try {
            // Create a new user
            $newUser = new User();
            $newUser->firstName = $request->input('fName');
            $newUser->middleName = $request->input('mName');
            $newUser->lastName = $request->input('lName');
            $newUser->email = $request->input('email');
            $newUser->suffix = $request->input('suffix');
            $newUser->mobileNo = $request->input('mobileNo');
            $newUser->gender = $request->input('gender');
            $newUser->birthday = $request->input('bday');
            $newUser->streetAddr = $request->input('sAddr');
            $newUser->city = $request->input('city');
            $newUser->postal = $request->input('postal');
            $newUser->user_type = $request->input('userType');

            if (!$newUser->save()) {
                return back()->withInput()->with('error', 'Failed to create user.');
            }

            // Create credentials
            $credentials = new Credential();
            $credentials->user_id = $newUser->id;
            $credentials->password = Hash::make($request->input('password'));

            if (!$credentials->save()) {
                return back()->withInput()->with('error', 'Failed to create user credentials.');
            }

            return redirect()->route('admin.home')->with('success', 'User successfully added.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function editUserPost(Request $request)
    {

        $userId = $request->input('userId');

        $user = User::findOrFail($request->userId);
        $user->firstName = $request->input('fName');
        $user->middleName = $request->input('mName');
        $user->lastName = $request->input('lName');
        $user->email = $request->input('email');
        $user->mobileNo = $request->input('mobileNo');
        $user->gender = $request->input('gender');
        $user->birthday = $request->input('bday');
        $user->streetAddr = $request->input('sAddr');
        $user->city = $request->input('city');
        $user->postal = $request->input('postal');
        $user->user_type = $request->input('userType');
        $user->save();

        if ($request->filled('password')) {
            $credential = Credential::firstOrNew(['user_id' => $userId]);
            $credential->password = bcrypt($request->password);
            $credential->save();
        }



        return redirect()->back()->with('success', 'User updated successfully!');
    }

    public function removeUserPost(Request $request)
    {
        $user = User::findOrFail($request->userId);

        DB::table('payments')->where('user_id', $user->id)->delete();

        DB::table('credentials')->where('user_id', $user->id)->delete();
        $user->delete();

        return redirect()->back()->with('success', 'User removed successfully!');
    }


    public function addSubscriptionPost(Request $request)
    {

        $sub = new Subscription();
        $sub->subscription = $request->input('plan');
        $sub->price = $request->input('price');

        if ($request->hasFile('thumbnail') && $request->file('thumbnail')->isValid()) {
            $fileName = time() . '.' . $request->file('thumbnail')->getClientOriginalExtension();
            $path = $request->file('thumbnail')->storeAs('Images', $fileName, 'public');
            $sub->thumbnail = '/storage/' . $path;
        }

        $sub->save();

        $subId = $sub->id;
        $detailsList = $request->input('details', []);

        foreach ($detailsList as $detailText) {
            SubscriptionDetails::create([
                'subscription_id' => $subId,
                'details' => $detailText
            ]);
        }

        return redirect()->back()->with('success', 'Subscription Added Successfully!');
    }

    public function editSubscriptionPost(Request $request)
    {
        $subscription = Subscription::findOrFail($request->subscription_id);

        // Update subscription details
        $subscription->subscription = $request->plan;
        $subscription->price = $request->price;

        if ($request->hasFile('thumbnail') && $request->file('thumbnail')->isValid()) {
            $fileName = time() . '.' . $request->file('thumbnail')->getClientOriginalExtension();
            $path = $request->file('thumbnail')->storeAs('Images', $fileName, 'public');
            $subscription->thumbnail = '/storage/' . $path;
        }

        $subscription->save();

        // Remove old details (optional)
        SubscriptionDetails::where('subscription_id', $subscription->id)->delete();

        // Save new details
        if ($request->details) {
            foreach ($request->details as $detailText) {
                SubscriptionDetails::create([
                    'subscription_id' => $subscription->id,
                    'details' => $detailText,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Subscription Updated Successfully!');
    }





    public function removeSubscriptionPost(Request $request)
    {
        try {
            $subscription = Subscription::findOrFail($request->subscription_id);
            $subscription->delete(); // This should now delete related records due to ON DELETE CASCADE

            return redirect()->back()->with('success', 'Subscription removed successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong. Please try again.');
        }
    }


    public function editSubscriptionDetail(Request $request)
    {
        $detail = SubscriptionDetails::find($request->detail_id);

        if ($detail) {
            $detail->delete();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Detail not found']);
    }

    public function cancelSubscription(Request $request)
    {
        $user = User::findOrFail($request->userId);

        // Delete all payments where user_id matches
        Payment::where('user_id', $user->id)->delete();

        return redirect()->route('adminUser')->with('success', 'Subscription has been cancelled.');
    }
}
