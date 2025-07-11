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

    public function me(Request $request)
    {
        return response()->json([
            'user' => auth()->user(),
            'roles' => auth()->user() ? auth()->user()->getRoleNames() : [],
        ]);
    }


    public function addCredits(Request $request)
    {
        $user = auth()->user(); // oppure JWTAuth::user()

        if (!$user->hasRole('user') && !$user->hasRole('admin')) {
            return response()->json(['message' => 'Non autorizzato'], 403);
        }

        $request->validate([
            'amount' => 'required|integer|min:1'
        ]);
        $amount = $request->input('amount');

        // Trova la riga crediti dell’utente (che ora esiste sempre!)
        $credito = \App\Models\ContattoCredito::where('idContatto', $user->idContatto)->first();

        // Aggiorna il campo crediti
        $credito->credito += $amount;
        $credito->save();

        return response()->json([
            'message' => 'Crediti aggiunti!',
            'credito' => $credito->credito
        ]);
    }


}
