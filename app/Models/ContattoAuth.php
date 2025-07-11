<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ContattoAuth extends Model
{
    use HasFactory;

    protected $table = "contattiAuth";
    protected $primaryKey = "idContattoAuth";

    protected $fillable = [
        'idContatto',
        'user',
        'sfida',
        'secretJWT',
        'inizioSfida',
        'obbligoCambio'
    ];

    // ---------------- PUBLIC ------------------

    /**
     * Controlla se esiste l'utente passato per login
     * @param string $user
     * @return boolean
     */
    public static function esisteUtenteValidoPerLogin($user)
    {

        $hashUser = hash('sha512', trim($user));

        $tmp = DB::table('contatti')
            ->join('contattiAuth', 'contatti.idContatto', '=', 'contattiAuth.idContatto')
            ->where('contatti.idContattoStato', '=', 1)
            ->where('contattiAuth.user', '=', $hashUser) // <-- USA L'HASH!
            ->select('contattiAuth.idContatto')
            ->get()
            ->count();

        return ($tmp > 0);
    }


    /**
     * Controlla se esiste l'utente passato
     * @param string $user
     * @return boolean
     */
    public static function esisteUtente($user)
    {
        $tmp = DB::table('contattiAuth')
            ->where('contattiAuth.user', '=', $user)
            ->select('contattiAuth.idContatto')
            ->get()
            ->count();

        return ($tmp > 0) ? true : false;
    }
}
