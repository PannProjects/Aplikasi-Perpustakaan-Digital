<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'Username' => ['required'],
            'Password' => ['required'],
        ]);

        $user = User::where('Username', $credentials['Username'])->first();

        if ($user && Hash::check($credentials['Password'], $user->Password)) {
            Auth::login($user);
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'Username' => 'Kredensial yang diberikan tidak cocok dengan data kami.',
        ]);
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'Username' => 'required|unique:users',
            'Password' => 'required|min:4',
            'Email' => 'required|email|unique:users',
            'NamaLengkap' => 'required',
            'Alamat' => 'required',
            'Role' => 'required|in:administrator,peminjam',
        ]);

        User::create([
            'Username' => $request->Username,
            'Password' => Hash::make($request->Password),
            'Email' => $request->Email,
            'NamaLengkap' => $request->NamaLengkap,
            'Alamat' => $request->Alamat,
            'Role' => $request->Role,
        ]);

        return redirect()->route('login')->with('success', 'Registrasi berhasil. Silakan login.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
