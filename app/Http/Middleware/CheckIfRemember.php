<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckIfRemember
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(Auth::check()){
            //dd(Auth::id());
            //Log::info("Via remember");
            $user = User::where('id', Auth::id())->first();
            Session()->put('user', $user);
            Session()->put('loginId', $user->id);
        } else{
            //dd(Auth::viaRemember(). " ". Auth::id());
        }
        return $next($request);
    }
}
