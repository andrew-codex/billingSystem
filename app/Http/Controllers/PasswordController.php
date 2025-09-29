<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class PasswordController extends Controller
{

    public function showChangeForm()
{
    if (!auth()->user()->must_change_password) {
        return redirect()->route('dashboard.index')
            ->with('info', 'You already changed your password.');
    }

    return view('/auth/force-change-password ');
}



    public function update(Request $request)
    {
        $request->validate([
            'password' => 'required|confirmed|min:8',
        ]);

        $user = auth()->user();
        $user->password = Hash::make($request->password);
        $user->must_change_password = false; 
        $user->save();

        return redirect()->route('dashboard.index')->with('success', 'Password updated successfully!');
    }
}
