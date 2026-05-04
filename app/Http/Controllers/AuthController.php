<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Menampilkan halaman login
    public function showLoginForm()
    {
        // Jika user sudah login, langsung arahkan ke dashboard
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        
        return view('welcome');
    }

    // Memproses data login
    public function login(Request $request)
    {
        // 1. Validasi input
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 2. Cek apakah user mencentang "Ingat saya"
        $remember = $request->has('remember');

        // 3. Proses autentikasi
        if (Auth::attempt($credentials, $remember)) {
            // Jika sukses, regenerate session untuk keamanan (mencegah session fixation)
            $request->session()->regenerate();

            // Arahkan ke dashboard
            return redirect()->intended('dashboard');
        }

        // 4. Jika gagal, kembalikan ke halaman login dengan error
        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    // Memproses logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}