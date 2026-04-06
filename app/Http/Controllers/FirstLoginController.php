<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class FirstLoginController extends Controller
{
    public function edit()
    {
        return view('auth.first-login');
    }

    public function update(Request $request)
    {
        $request->validate([
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $user = $request->user();
        $user->password = Hash::make($request->password);
        $user->is_first_login = false;
        $user->save();

        return redirect()->route('dashboard')->with('success', 'Password berhasil diperbarui.');
    }
}