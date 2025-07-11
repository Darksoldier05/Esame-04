<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Indirizzo extends Model
{
    // Nome tabella (opzionale, solo se diversa da 'indirizzi')
    protected $table = 'indirizzi';

    // Chiave primaria (opzionale, solo se diversa da 'id')
    protected $primaryKey = 'id';

    // Quali campi sono fillable
    protected $fillable = [
        'idContatto',
        // aggiungi qui gli altri campi: via, citta, cap, provincia, ecc.
    ];

    public $timestamps = true;

    // Relazione inversa con Contatto
    public function contatto()
    {
        return $this->belongsTo(Contatto::class, 'idContatto', 'idContatto');
    }
}
