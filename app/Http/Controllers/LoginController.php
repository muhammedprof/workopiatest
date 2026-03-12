<?php

namespace App\Http\Controllers;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(): View {
  return view('auth.login');
    }
     public function authenticate(Request $request): RedirectResponse
    {
    // Validate the request data
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required|string',
    ]);

    //dd($credentials);
    // Attempt to authenticate the user
    if (Auth::attempt($credentials)) {
        // Authentication passed, regenerate the session        $request->session()->regenerate();
        $request->session()->regenerate();
        return redirect()->route('jobs.index');
    }
    return back()->withErrors([
        'email' => 'The provided credentials do not match our records.',
    ]);
    }


    public function logout(Request $request): RedirectResponse
    {
    Auth::logout(); // Log out the user

    $request->session()->invalidate(); // Invalidate the session
    $request->session()->regenerateToken(); // Regenerate the CSRF token

    return redirect('/');
    }
}   