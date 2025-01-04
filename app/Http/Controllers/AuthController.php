<?php

namespace App\Http\Controllers;

use App\Models\UserInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:100|unique:users_info',
            'email' => 'required|string|email|unique:users_info|max:100',
            'password' => 'required|string|min:8',
        ]);

        $user = new UserInfo();
        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        Auth::login($user);
        return redirect()->route('home');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

<<<<<<< HEAD
        if (Auth::attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();

            // Check if user is admin
            if (Auth::user()->email === 'adminb@gmail.com') {
                return redirect()->route('admin.index');
            }

            return redirect()->route('home');
        }

        return back()->withErrors([
            'email' => 'The provided credentials are incorrect.',
        ]);
=======
        if (!Auth::attempt($request->only('email', 'password'))) {
            return back()->withErrors([
                'email' => 'The provided credentials are incorrect.',
            ]);
        }

        return redirect()->route('home');
>>>>>>> 9f54a7f70537ac620d030b65705c3379f4ec70bb
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
<<<<<<< HEAD
        return redirect('/');
=======
        return redirect()->route('login');
>>>>>>> 9f54a7f70537ac620d030b65705c3379f4ec70bb
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}
