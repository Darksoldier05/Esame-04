<?php

namespace App\Helpers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Models\Contatto;
use Illuminate\Support\Facades\DB;

class AppHelper
{
    // --- PUBLIC --------------------------------------------------------

    /**
     * Toglie il required alle rules di aggiornamento
     *
     * @param array $rules
     * @return array
     */
    public static function aggiornaRegoleHelper($rules)
    {
        $newRules = array();
        foreach ($rules as $key => $value) {
            $newRules[$key] = str_replace("required|", "", $value);
        }
        return $newRules;
    }

    // -----------------------------------------------------------

    /**
     * Unisci password e sale e fai HASH
     *
     * @param string $testo da cifrare
     * @param string $chiave usata per cifrare
     * @return string
     */
    public static function cifra($testo, $chiave)
    {
        $testoCifrato = AesCtr::encrypt($testo, $chiave, 256);
        return base64_encode($testoCifrato);
    }

    // -----------------------------------------------------------

    /**
     * Estrae i nomi dei campi della tabella sul DB
     *
     * @param array $tabella
     * @return array
     */
    public static function colonneTabellaDB($tabella)
    {
        $SQL = "SELECT COLUMN_NAME FROM information_schema.columns WHERE table_schema='" .
            DB::connection()->getDatabaseName() . "' AND table_name='" . $tabella . "';";
        $tmp = DB::select($SQL);
        return $tmp;
    }

    // -----------------------------------------------------------

    /**
     * Estrae i nomi dei campi della tabella sul DB
     *
     * @param string $password
     * @param string $sale
     * @param string $sfida
     * @return string
     */
    public static function creaPasswordCifrata($password, $sale, $sfida)
    {
        $hashPasswordESale = AppHelper::nascondiPassword($password, $sale);
        $hashFinale = AppHelper::cifra($hashPasswordESale, $sfida);
        return $hashFinale;
    }

    // -----------------------------------------------------------

    /**
     * Toglie il required alle rules di aggiornamento
     *
     * @param string $secretJWT come chiave di cifratura
     * @param integer $idContatto
     * @param string $secretJWT
     * @param integer $usaDa unixtime abilitazione uso token
     * @param integer $scade unixtime scadenza uso token
     * @return string
     */
    public static function creaTokenSessione($idContatto, $secretJWT, $usaDa = null, $scade = null)
    {
        $maxTime = 15 * 24 * 60 * 60; // il token scade sempre dopo 15gg max
        $recordContatto = Contatto::where("idContatto", $idContatto)->first();
        $st = time();
        $nbf = ($usaDa == null) ? $st : $usaDa;
        $exp = ($scade == null) ? $st + $maxTime : $scade;

        $arr = array(
            "iss" => 'https://www.codex.it',
            "iat" => $st,
            "nbf" => $nbf,
            "exp" => $exp,
            "data" => [
                "idContatto" => $idContatto,
                "nome" => trim($recordContatto->nome),
                "cognome" => trim($recordContatto->cognome)
            ]
        );
        $token = JWT::encode($arr, $secretJWT, 'HS512');
        return $token;
    }


    // -----------------------------------------------------------

    /**
     * Unisci password e sale e fai HASH
     *
     * @param string $testo da decifrare
     * @param string $chiave usata per decifrare
     * @return string
     */
    public static function decifra($testoCifrato, $chiave)
    {
        $testoCifrato = base64_decode($testoCifrato);
        return AesCtr::decrypt($testoCifrato, $chiave, 256);
    }

    // -----------------------------------------------------------

    /**
     * Controlla se è amministratore
     *
     * @param string $idGruppo
     * @return boolean
     */
    public static function isAdmin($idGruppo)
    {
        return ($idGruppo == 1) ? true : false;
    }

    // -----------------------------------------------------------

    /**
     * Unisci password e sale e fai HASH
     *
     * @param string $password
     * @param string $sale
     * @return string
     */
    public static function nascondiPassword($password, $salt)
    {
        return hash('sha512', $salt . $password);
    }


    // -----------------------------------------------------------

    /**
     * Controlla se esiste l'utente passato
     *
     * @param boolean $successo TRUE se la richiesta è andata a buon fine
     * @param integer $codice STATUS CODE della richiesta
     * @param array $dati Dati richiesti
     * @param string $messaggio
     * @param array $errori
     * @return array
     */
    public static function rispostaCustom($dati, $msg = null, $err = null)
    {
        $response = array();
        $response["data"] = $dati;
        if ($msg != null)
            $response["message"] = $msg;
        if ($err != null)
            $response["error"] = $err;
        return $response;
    }

    // -----------------------------------------------------------

    /**
     * Valida Token
     *
     * @param string $token
     * @param string $messaggio
     * @param array $errori
     * @return object
     */
    public static function validaToken($token, $secretJWT, $sessione)
    {
        $rit = null;
        $payload = JWT::decode($token, new Key($secretJWT, 'HS512'));
        $idContatto = null;
        if (isset($payload->data) && isset($payload->data->idContatto)) {
            $idContatto = $payload->data->idContatto;
        } elseif (isset($payload->idContatto)) {
            $idContatto = $payload->idContatto;
        }
        if ($payload->iat <= $sessione->inizioSessione) {
            if ($idContatto && $idContatto == $sessione->idContatto) {
                $rit = $payload;
            } else {
            }
        } else {
        }
        return $rit;
    }



}