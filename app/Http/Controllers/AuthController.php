<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function welcome()
    {
        return view('welcome');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => [
                'required',
                'string',
                'confirmed',
                Password::min(10)->mixedCase()->numbers()->symbols(),
            ],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => 'customer',
            'password' => Hash::make($data['password']),
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        AuditLog::create([
            'user_id' => $user->id,
            'event' => 'register',
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('invoices.index')->with('status', 'Welcome to MaliHub.');
    }

    public function forgotPassword()
    {
        return view('auth.forgot-password');
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
