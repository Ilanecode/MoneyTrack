<?php

namespace App\Http\Controllers;

use App\Models\Audit;
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

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'caissier', // rôle par défaut
        ]);

        Auth::login($user);

        return redirect()->route('dashboard');
    }

    public function login(Request $request)
    {
        $request->validate([
            'identifiant' => ['required', 'string'],
            'password' => ['required'],
        ]);

        $login = $request->input('identifiant');
        // Si ça ressemble à un email, on cherche dans la colonne 'email', sinon dans 'name'
        $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'name';

        $credentials = [
            $fieldType => $login,
            'password' => $request->input('password')
        ];

        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();
            Audit::log('connexion', 'Utilisateur '.Auth::user()->name.' connecté', 'Session', Auth::id());
            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'identifiant' => 'Les identifiants ne correspondent pas à nos enregistrements.',
        ])->onlyInput('identifiant');
    }

    public function logout(Request $request)
    {
        Audit::log('déconnexion', 'Utilisateur déconnecté');
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
