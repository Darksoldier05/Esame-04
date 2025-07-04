<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        $user = auth()->user();

        // Ottieni i ruoli dell'utente (array di nomi)
        $roles = $user->getRoleNames();

        return response()->json([
            'user' => $user,
            'roles' => $roles,
        ]);
    }

    public function addCredits(Request $request)
    {
        $user = auth()->user(); // oppure JWTAuth::user() se usi direttamente la facade

        if (!$user->hasRole('user') && !$user->hasRole('admin')) {
            return response()->json(['message' => 'Non autorizzato'], 403);
        }

        $request->validate([
            'amount' => 'required|integer|min:1'
        ]);

        $user->credits += $request->input('amount');
        $user->save();

        return response()->json([
            'message' => 'Crediti aggiunti!',
            'credits' => $user->credits
        ]);
    }

}
