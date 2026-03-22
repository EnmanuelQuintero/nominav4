<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLogin()
    {
        return view('inicio.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            'password' => 'required'
        ]);

        $credentials = [
            'nombre' => $request->nombre,
            'password' => $request->password,
            'estado' => 1
        ];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('dashboard');
        }

        return back()->withErrors([
            'nombre' => 'Credenciales incorrectas o usuario inactivo'
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}