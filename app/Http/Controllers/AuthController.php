<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login()
    {
        if (Auth::check()) {
            return $this->redirectUser(Auth::user());
        }
        return view('auth.login');
    }

    public function loginCheck(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();
            
            $user = Auth::user();
            
            if ($user->status !== 'active') {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Your account is currently inactive.',
                ]);
            }

            // Log activity
            \App\Models\ActivityLog::log('Login', 'User logged in successfully.');

            return $this->redirectUser($user);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        \App\Models\ActivityLog::log('Logout', 'User logged out.');
        
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    protected function redirectUser($user)
    {
        switch ($user->role) {
            case 'super_admin':
                return redirect('/super-admin/dashboard');
            case 'admin':
                return redirect('/admin/dashboard');
            case 'resident':
                return redirect('/resident/dashboard');
            case 'guard':
                return redirect('/guard/dashboard');
            default:
                return redirect('/login');
        }
    }
}