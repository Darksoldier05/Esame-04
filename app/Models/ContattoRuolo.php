<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContattoRuolo extends Model
{
    protected $table = "contattiRuoli";
    protected $primaryKey = "idContattoRuolo";

    protected $fillable = [
        'nome',
        'descrizione',
        // altri campi se servono...
    ];

    public function contatti()
    {
        return $this->belongsToMany(
            Contatto::class,
            'contatti_contattiRuoli',
            'idContattoRuolo',
            'idContatto'
        );
    }
}
