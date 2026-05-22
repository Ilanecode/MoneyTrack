<?php

namespace App\Http\Controllers;

use App\Models\Audit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(10);
        $totalUsersCount = User::count();
        return view('users.index', compact('users', 'totalUsersCount'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role'     => 'required|in:admin,caissier',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
        ]);

        Audit::log('création', "Utilisateur créé : {$user->name} ({$user->role})", 'User', $user->id);

        return redirect()->route('users.index')->with('success', 'Utilisateur créé avec succès.');
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'role'     => 'required|in:admin,caissier',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $changes = [];

        if ($user->role !== $request->role) {
            if ($user->role === 'admin' && User::where('role', 'admin')->count() <= 1) {
                return redirect()->back()->with('error', 'Action refusée : il doit y avoir au moins un administrateur dans le système.');
            }
            $changes[] = "rôle changé de {$user->role} → {$request->role}";
            $user->role = $request->role;
        }

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
            $changes[] = 'mot de passe réinitialisé';
        }

        $user->save();

        $detail = !empty($changes) ? implode(', ', $changes) : 'aucun changement';
        Audit::log('modification', "Utilisateur modifié : {$user->name} — {$detail}", 'User', $user->id);

        return redirect()->route('users.index')->with('success', 'Utilisateur mis à jour avec succès.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $id   = $user->id;
        $name = $user->name;
        $user->delete();

        Audit::log('suppression', "Utilisateur supprimé : {$name}", 'User', $id);

        return redirect()->route('users.index')->with('success', 'Utilisateur supprimé.');
    }
}
