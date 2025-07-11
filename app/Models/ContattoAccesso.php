<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContattoAccesso extends Model
{
    use HasFactory;

    protected $table = "contattiAccessi";
    protected $fillable = ["idContatto", "autenticato", "ip"];

    //--- PUBLIC ---------------------------------------------------------

    /**
     * Aggiungi tentativo fallito per l'idContatto
     *
     * @param string $idContatto
     */
    public static function aggiungiAccesso($idContatto)
    {
        ContattoAccesso::eliminaTentativi($idContatto);
        return ContattoAccesso::nuovoRecord($idContatto, 1);
    }

    /**
     * Aggiungi tentativo fallito per l'idContatto
     *
     * @param string $idContatto
     */
    public static function aggiungiTentativoFallito($idContatto)
    {
        return ContattoAccesso::nuovoRecord($idContatto, 0);
    }

    /**
     * Conta quanti tentativi per l'idContatto sono registrati
     *
     * @param string $idContatto
     * @return integer
     */
    public static function contaTentativi($idContatto)
    {
        $tmp = ContattoAccesso::where("idContatto", $idContatto)
            ->where("autenticato", 0)
            ->count();
        return $tmp;
    }

    //--- PROTECTED ------------------------------------------------------

    /**
     * Inserisce un nuovo record di accesso/tentativo per idContatto
     *
     * @param string $idContatto
     * @param boolean $autenticato
     * @return \App\Models\ContattoAccesso
     */
    protected static function nuovoRecord($idContatto, $autenticato)
    {
        $tmp = ContattoAccesso::create([
            "idContatto" => $idContatto,
            "autenticato" => $autenticato,
            "ip" => request()->ip()
        ]);
        return $tmp;
    }

    /**
     * Metodo statico per eliminare tutti i tentativi di un contatto
     */
    public static function eliminaTentativi($idContatto)
    {
        return self::where('idContatto', $idContatto)->delete();
    }
}
