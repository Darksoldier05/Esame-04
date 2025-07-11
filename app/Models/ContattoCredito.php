<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContattoCredito extends Model
{
    protected $table = 'contattiCrediti';
    protected $primaryKey = 'idContatto';
    public $incrementing = false;
    protected $keyType = 'int';

    protected $fillable = [
        'idContatto',
        'credito',
    ];

    // Relazione con Contatto
    public function contatto()
    {
        return $this->belongsTo(Contatto::class, 'idContatto', 'idContatto');
    }
}
