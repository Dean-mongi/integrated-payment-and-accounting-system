<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            AuditLog::create([
                'user_id' => Auth::id(),
                'event' => 'login',
                'ip_address' => $request->ip(),
            ]);

            $home = in_array(Auth::user()->role, ['admin', 'accountant'], true)
                ? route('dashboard')
                : route('invoices.index');

            return redirect()->intended($home);
        }

        return back()->withErrors(['email' => 'Invalid login credentials.'])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        AuditLog::create([
            'user_id' => Auth::id(),
            'event' => 'logout',
            'ip_address' => $request->ip(),
        ]);

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
