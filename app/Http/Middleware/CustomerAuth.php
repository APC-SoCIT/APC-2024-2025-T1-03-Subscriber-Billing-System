<?php

namespace App\Http\Middleware;

use App\Models\Credential;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class CustomerAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Session::has('loginId')) {
            if (Session::has('loginId')) {
                $user = $request->session()->get('user');

                if ($user && $user->user_type === 'Admin') {
                    return redirect()->route('admin.home')->with('error', 'You do not have access to this page.');
                }
                return $next($request);
            }

            // If the user is not logged in, redirect them to the login page
            return redirect('/')->with('error', 'You must be logged in to access this page.');
        } else {
            $studentNumber = $request->email;
            $password = $request->password;
            $user = User::where('email', $studentNumber)->first();

            if ($user) {
                $credentials = Credential::where('user_id', $user->id)
                    ->where(function ($query) {
                        $query->where('is_deleted', 0)
                            ->orWhereNull('is_deleted');
                    })
                    ->first();

                if ($credentials && Hash::check($password, $credentials->password)) {
                    $request->session()->put('user', $user);
                    $request->session()->put('loginId', $user->id);

                    return $next($request);
                }

                return redirect('/login')->with('error', 'Invalid credentials.');
            }

            return redirect('/login')->with('error', 'Invalid credentials.');
        }
    }
}
