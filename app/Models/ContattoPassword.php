<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContattoPassword extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "contattiPassword";
    protected $primaryKey = "idContattoPassword";

    protected $fillable = [
        'idContatto',
        'psw',
        'sale'
    ];

    // --- PUBLIC --------------------------------------------

    /**
     * Ritorna il record della password attualmente usata
     * @param integer $idContatto
     * @return \App\Models\ContattoPassword
     */
    public static function passwordAttuale($idContatto)
    {
        $record = ContattoPassword::where("idContatto", $idContatto)
            ->orderBy("idContattoPassword", "desc")
            ->firstOrFail();
        return $record;
    }
}
