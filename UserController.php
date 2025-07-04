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
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'Utente non trovato'], 404);
        }

        // Se vuoi permettere solo all'admin di aggiornare altri utenti:
        if (!auth()->user()->hasRole('admin')) {
            return response()->json(['message' => 'Non autorizzato'], 403);
        }

        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:users,email,' . $user->id,
            'password' => 'sometimes|nullable|string|min:6|confirmed'
        ]);

        if ($request->has('name'))
            $user->name = $request->name;
        if ($request->has('email'))
            $user->email = $request->email;
        if ($request->has('password'))
            $user->password = bcrypt($request->password);

        $user->save();

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
