<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Visualizza tutti gli utenti (solo admin)
    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }

    // Visualizza un utente specifico (facoltativo)
    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'Utente non trovato'], 404);
        }

        return response()->json($user);
    }

    // Aggiorna i dati dell’utente autenticato
    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
            // altri campi se servono
        ]);

        $user->update($validated);

        return response()->json([
            'message' => 'Profilo aggiornato',
            'user' => $user
        ]);
    }

    // Elimina un utente (solo admin)
    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'Utente non trovato'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'Utente eliminato con successo']);
    }

    // Aggiunge crediti all’utente autenticato
    public function addCredits(Request $request)
    {
        $user = auth()->user();
        $validated = $request->validate([
            'credits' => 'required|integer|min:1'
        ]);

        $user->credits += $validated['credits'];
        $user->save();

        return response()->json([
            'message' => 'Crediti aggiunti',
            'credits' => $user->credits
        ]);
    }
}
