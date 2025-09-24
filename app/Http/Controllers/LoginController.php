<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Auth;
class LoginController extends Controller
{

public function showLoginForm(){
    return view('pages.welcome');
}


public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->filled('remember'); 

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

           
            if ($remember) {
                $rememberTokenName = Auth::getRecallerName();
                $rememberTokenValue = Auth::user()->getRememberToken();
                Cookie::queue(
                    Cookie::make(
                        $rememberTokenName,
                        $rememberTokenValue,
                        60 * 24 * 7, 
                        null,
                        null,
                        true,   
                        true    
                    )
                );
            }

            return redirect()->intended(route('dashboard.index'))->with('success', 'Login successful!');
        }

    return back()->with('error', 'Invalid email or password.')->onlyInput('email');

    }

        public function logout(Request $request)
        {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            
            cookie()->queue(cookie()->forget(Auth::getRecallerName()));

            return redirect()->route('login')->with('success', 'Logout successful!');
        }

}
