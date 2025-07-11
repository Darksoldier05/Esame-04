<?php

namespace App\Http\Controllers;

use App\Models\Contatto;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Visualizza tutti gli utenti (solo admin)
    public function index()
    {
        $users = Contatto::all();
        return response()->json($users);
    }

    // Visualizza un utente specifico
    public function show($id)
    {
        $user = Contatto::find($id);

        if (!$user) {
            return response()->json(['error' => 'Utente non trovato'], 404);
        }

        return response()->json($user);
    }

    // Aggiorna i dati di un utente
    public function update(Request $request, $id)
    {
        $user = Contatto::find($id);
        if (!$user) {
            return response()->json(['message' => 'Utente non trovato'], 404);
        }

        // Solo admin può aggiornare altri utenti
        if (!auth()->user()->hasRole('admin')) {
            return response()->json(['message' => 'Non autorizzato'], 403);
        }

        $request->validate([
            'nome' => 'sometimes|required|string|max:45',
            'cognome' => 'sometimes|required|string|max:45',
            'sesso' => 'sometimes|integer|in:0,1,2'
        ]);

        if ($request->has('nome'))
            $user->nome = $request->nome;
        if ($request->has('cognome'))
            $user->cognome = $request->cognome;
        if ($request->has('sesso'))
            $user->sesso = $request->sesso;

        $user->save();

        return response()->json([
            'message' => 'Profilo aggiornato',
            'user' => $user
        ]);
    }

    // Elimina un utente (solo admin)
    public function destroy($id)
    {
        $user = Contatto::find($id);

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
