<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Http\Controllers\Controller;
use App\Models\ContattoAuth;
use App\Models\ContattoPassword;
use App\Models\ContattoAccesso;
use App\Models\ContattoSessione;
use App\Models\Configurazione;
use Illuminate\Support\Str;

class AccediController extends Controller
{
    // Cerca se esiste uno user nel DB
    public function searchMail($utente)
    {
        $tmp = (ContattoAuth::esisteUtente($utente)) ? true : false;
        return AppHelper::rispostaCustom(['exists' => $tmp]);

    }

    // ----------------------------------------------------------------------------------------------------------
    /**
     * Crea il token per sviluppo
     *
     * @param string $utente
     */
    public static function testLogin($hashUser, $hashSalePsw)
    {
        // Cerca auth e password come prima...
        $auth = ContattoAuth::where('user', $hashUser)->first();
        if (!$auth) {
            return response()->json(['error' => 'Utente non trovato'], 404);
        }

        $recordPassword = ContattoPassword::passwordAttuale($auth->idContatto);
        if (!$recordPassword) {
            return response()->json(['error' => 'Password non trovata'], 404);
        }

        $salt = $recordPassword->sale;
        $passwordDb = $recordPassword->psw;
        $hashCalcolato = AppHelper::nascondiPassword($hashSalePsw, $salt);

        // Puoi restituire tutte le info che vuoi vedere per debug
        return response()->json([
            'hashUser_input' => $hashUser,
            'password_input' => $hashSalePsw,
            'salt_db' => $salt,
            'password_db' => $passwordDb,
            'hash_calcolato' => $hashCalcolato,
            'match' => ($hashCalcolato == $passwordDb)
        ]);
    }


    // Punto di ingresso del login (GET /accedi/{utente}/{hash?})
    public function show($utente, $hash = null)
    {
        if ($hash == null) {
            return self::controlloUtente($utente);
        } else {
            return self::controlloPassword($utente, $hash);
        }
    }

    // ====================== STATICHE ======================

    // Crea un token per sviluppo (esempio)
    public static function testToken()
    {
        // User e password in chiaro (per il test: normalmente arrivano in input)
        $utente = hash('sha512', trim("Admin@Utente"));
        $password = hash('sha512', trim("Password123!"));
        $sfida = hash('sha512', trim("sfida"));

        // Prendi l'utente dal database
        $auth = ContattoAuth::where('user', hash('sha512', trim($utente)))->firstOrFail();

        if ($auth != null) {
            // Aggiorna solo la sfida e il timestamp (MAI la secretJWT!)
            $auth->sfida = $sfida;
            $auth->inizioSfida = time();
            $auth->save();
        }

        // Prendi il sale corrente della password dal db
        $recordPassword = ContattoPassword::passwordAttuale($auth->idContatto);
        if ($recordPassword != null) {
            $sale = $recordPassword->sale;
        } else {
            $sale = hash('sha512', trim(Str::random(200)));
        }

        // Calcola la "password nascosta" da restituire (se serve)
        $cipher = AppHelper::nascondiPassword($password, $sale);

        // **Prendi la secret JWT dal DB**
        $secretJWT = $auth->secretJWT;

        // Crea il token con la secret presa dal db
        $tk = AppHelper::creaTokenSessione($auth->idContatto, $secretJWT);
        $dati = array("token" => $tk, "xLogin" => $cipher);

        // Aggiorna la sessione con il nuovo token e inizioSessione
        $sessione = ContattoSessione::where("idContatto", $auth->idContatto)->firstOrFail();
        $sessione->token = $tk;
        $sessione->inizioSessione = time();
        $sessione->save();

        return AppHelper::rispostaCustom($dati);
    }


    // Verifica il token ad ogni chiamata
    public static function verificaToken($token)
    {
        $rit = null;
        $sessione = ContattoSessione::datiSessione($token);
        if ($sessione != null) {
            $inizioSessione = $sessione->inizioSessione;
            $durataSessione = Configurazione::leggiValore("durataSessione");
            $scadenzaSessione = $inizioSessione + $durataSessione;
            if (time() < $scadenzaSessione) {
                $auth = ContattoAuth::where('idContatto', $sessione->idContatto)->first();
                if ($auth != null) {
                    $secretJWT = $auth->secretJWT;
                    $payload = AppHelper::validaToken($token, $secretJWT, $sessione);
                    if ($payload != null) {
                        $rit = $payload;
                    } else {
                        abort(403, "TK_0006");
                    }
                } else {
                    abort(403, "TK_0005");
                }
            } else {
                abort(403, "TK_0004");
            }
        } else {
            abort(403, "TK_0003");
        }
        return $rit;
    }

    // ====================== PROTECTED ======================

    // Controlla validità utente, genera e restituisce sale/sfida
    protected static function controlloUtente($utente)
    {
        $sfida = hash('sha512', trim(Str::random(200)));
        $sale = hash('sha512', trim(Str::random(200)));
        if (ContattoAuth::esisteUtenteValidoPerLogin($utente)) {
            $auth = ContattoAuth::where('user', hash('sha512', trim($utente)))->first();
            $auth->sfida = $sfida;
            $auth->secretJWT = hash('sha512', trim(Str::random(200)));
            $auth->inizioSfida = time();
            $auth->save();

            $recordPassword = ContattoPassword::passwordAttuale($auth->idContatto);
            $recordPassword->sale = $sale;
            $recordPassword->save();
        }
        // Se non esiste, genera sale/sfida fake
        //$dati = array("sfida" => $sfida, "sale" => $sale);
        $dati = array("sale" => $sale);
        return AppHelper::rispostaCustom($dati);
    }

    // Punto di ingresso login, verifica la password hashata (hashClient)
    protected static function controlloPassword($utente, $hashClient)
    {
        if (ContattoAuth::esisteUtenteValidoPerLogin($utente)) {
            $auth = ContattoAuth::where('user', hash('sha512', trim($utente)))->first();
            $secretJWT = $auth->secretJWT;
            $inizioSfida = $auth->inizioSfida;
            $durataSfida = Configurazione::leggiValore("durataSfida");
            $maxTentativi = Configurazione::leggiValore("maxLoginErrati");
            $scadenzaSfida = $inizioSfida + $durataSfida;
            if (time() < $scadenzaSfida) {
                $tentativi = ContattoAccesso::contaTentativi($auth->idContatto);
                if ($tentativi < $maxTentativi - 1) {
                    $recordPassword = ContattoPassword::passwordAttuale($auth->idContatto);
                    $password = $recordPassword->psw; // già hashato (SHA512(sale + password))
                    // $sale = $recordPassword->sale; // non serve per il confronto
                    if ($hashClient == $password) {
                        // Login corretto: creo token e registro accesso
                        $tk = AppHelper::creaTokenSessione($auth->idContatto, $secretJWT);
                        ContattoAccesso::eliminaTentativi($auth->idContatto);
                        ContattoAccesso::aggiungiAccesso($auth->idContatto);
                        ContattoSessione::eliminaSessione($auth->idContatto);
                        ContattoSessione::aggiornaSessione($auth->idContatto, $tk);
                        $dati = array("tk" => $tk);
                        return AppHelper::rispostaCustom($dati);
                    } else {
                        ContattoAccesso::aggiungiTentativoFallito($auth->idContatto);
                        abort(403, "ERR_L004");
                    }
                } else {
                    abort(403, "ERR_L003");
                }
            } else {
                abort(403, "ERR_L002");
            }
        } else {
            abort(403, "ERR_L001");
        }
    }

}