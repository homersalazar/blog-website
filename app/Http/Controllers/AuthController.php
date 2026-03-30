<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        try {
            $credentials = $request->only('email', 'password');
            // Now attempt authentication
            if (auth()->attempt($credentials)) {
                return redirect()->route('home')
                    ->with('success', 'Welcome back, ' . Auth::user()->name . '!');
            }
            return redirect()->back()
                ->with('error', 'Invalid credentials')
                ->withInput();
        } catch (\Exception $e) {
            Log::error('login error', ['exception' => $e]);
            return redirect()->back()->withErrors([
                'error' => 'Something went wrong. Please try again later.'
            ]);
        }
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'fullname' => 'required|string|max:255',
            'email' => 'required|email:rfc,dns|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        DB::beginTransaction();

        try {
            $user = new User();
            $user->name = ucwords($request->input('fullname')); // or $user->fullname if your column is fullname
            $user->email = $request->input('email');
            $user->password = Hash::make($request->input('password'));
            $user->save();

            DB::commit();
            return redirect()->route('login')->with('success', 'Registration successful! Please log in.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Registration error: ', ['exception' => $e]);
            return redirect()->back()->withErrors([
                'error' => 'Something went wrong. Please try again later.'
            ])->withInput();
        }
    }

    public function logout()
    {
        Session::flush();
        Auth::logout();
        return redirect()->route('home')->with('success', 'Logged out successfully!');
    }

}
