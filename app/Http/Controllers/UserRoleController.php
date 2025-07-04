<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserRoleController extends Controller
{
    // Metodo per assegnare un ruolo all'utente
    public function assignRole(Request $request, $id)
    {
        // Validazione semplice
        $request->validate([
            'role' => 'required|string'
        ]);

        // Trova l'utente
        $user = User::findOrFail($id);

        // Assegna il ruolo (aggiunge senza togliere gli altri)
        $user->assignRole($request->role);

        return response()->json([
            'message' => "Ruolo '{$request->role}' assegnato a {$user->name}",
            'user' => $user->name,
            'roles' => $user->getRoleNames()
        ]);
    }
}
