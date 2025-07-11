<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contatto;
use App\Models\ContattoAuth;
use App\Models\ContattoPassword;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    public function register($utente, Request $request)
    {
        // 1. Validazione dei dati (ora niente più user/email nel body)
        $request->validate([
            'nome' => 'required|string|max:45',
            'cognome' => 'required|string|max:45',
            'sesso' => 'nullable|integer',
            'password' => 'required|string|min:8|confirmed'
        ]);

        // 2. Hash della mail (passata nella route)
        $mail_hash = hash('sha512', trim($utente));

        // 3. Controlla se l'utente già esiste
        if (\App\Models\ContattoAuth::where('user', $mail_hash)->exists()) {
            return response()->json(['message' => 'Questa email è già registrata'], 422);
        }

        // 4. Crea record in contatti (anagrafica)
        $contatto = Contatto::create([
            'nome' => $request->nome,
            'cognome' => $request->cognome,
            'sesso' => $request->sesso,
        ]);

        // 5. Assegna ruolo "user"
        $contatto->assignRole('user');

        // 6. Genera secretJWT e sale
        $secretJWT = hash('sha512', \Illuminate\Support\Str::random(60));
        $sale = hash('sha512', \Illuminate\Support\Str::random(60));

        // 7. Crea record in contattiAuth
        $contattoAuth = ContattoAuth::create([
            'idContatto' => $contatto->idContatto,
            'user' => $mail_hash,
            'secretJWT' => $secretJWT,
            'obbligoCambio' => 0
        ]);

        // 8. Hash della password + sale
        $passwordHashed = hash('sha512', $sale . $request->password);

        // 9. Crea record in contattiPassword
        \App\Models\ContattoPassword::create([
            'idContatto' => $contatto->idContatto,
            'psw' => $passwordHashed,
            'sale' => $sale
        ]);

        // 10. Crea crediti base 0
        \App\Models\ContattoCredito::create([
            'idContatto' => $contatto->idContatto,
            'crediti' => 0
        ]);

        // 11. Risposta di successo
        return response()->json([
            'message' => 'Registrazione completata!',
            'contatto' => $contatto,
            'auth' => $contattoAuth
        ], 201);
    }

}
