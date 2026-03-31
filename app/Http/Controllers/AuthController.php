<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
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

    public function login(LoginUserRequest $request)
    {
        $credentials = $request->only('email', 'password');
        // Now attempt authentication
        if (auth()->attempt($credentials)) {
            return redirect()->route('home')
                ->with('success', 'Welcome back, ' . Auth::user()->name . '!');
        }

        return redirect()->back()
            ->with('error', 'Invalid credentials')
            ->withInput();
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(RegisterUserRequest $request)
    {
        DB::beginTransaction();

        try {

            User::create([
                'name' => ucwords($request->fullname),
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

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
