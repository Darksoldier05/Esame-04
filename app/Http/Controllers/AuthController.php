<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    // LOGIN: restituisce il token JWT se le credenziali sono valide
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 400);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        return response()->json(compact('token'));
    }


    // LOGOUT: invalida il token JWT corrente
    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'Logout effettuato con successo']);
    }

    // ME: restituisce i dati dell'utente autenticato
    public function me()
    {
        return response()->json(auth()->user());
    }

    // Funzione ausiliaria per formattare la risposta con il token JWT
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        // Validazione dei dati che vuoi accettare
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:users,email,' . $user->id,
            'password' => 'sometimes|required|string|min:6|confirmed'
        ]);

        // Aggiornamento dei campi
        if ($request->has('name')) {
            $user->name = $request->name;
        }
        if ($request->has('email')) {
            $user->email = $request->email;
        }
        if ($request->has('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        return response()->json([
            'message' => 'Profilo aggiornato',
            'user' => $user
        ]);
    }

    public function addCredits(Request $request)
    {
        $user = auth()->user();

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
