<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Http\Controllers\AccediController;
use App\Models\Contatto;
use Illuminate\Support\Facades\Auth;

class Autenticazione
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $header = $request->header('Authorization', '');
        $token = null;
        if (preg_match('/Bearer\s(\S+)/', $header, $matches)) {
            $token = $matches[1];
        }

        if (!$token) {
            return response()->json(['message' => 'Token mancante'], 401);
        }

        $payload = AccediController::verificaToken($token);
        if ($payload) {
            $contatto = Contatto::where("idContatto", $payload->data->idContatto)->first();
            if ($contatto && $contatto->idContattoStato == 1) {
                Auth::login($contatto);
                $contatto->getRoleNames();
                return $next($request);
            } else {
                return response()->json(['message' => 'Utente disabilitato o non trovato'], 403);
            }
        } else {
            return response()->json(['message' => 'Token non valido'], 403);
        }
    }

}
