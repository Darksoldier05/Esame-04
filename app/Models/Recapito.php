<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recapito extends Model
{
    // Nome della tabella (opzionale se si chiama 'recapiti')
    protected $table = 'recapiti';

    // Chiave primaria (opzionale se è 'id')
    protected $primaryKey = 'id';

    // Se non vuoi i campi updated_at/created_at, imposta a false
    public $timestamps = true;

    // Quali colonne possono essere assegnate in massa
    protected $fillable = [
        'idContatto',   // chiave esterna verso contatti
        // aggiungi qui gli altri campi della tabella recapiti, ad esempio:
        // 'tipo', 'valore', 'note'
    ];

    // Relazione inversa verso Contatto
    public function contatto()
    {
        return $this->belongsTo(Contatto::class, 'idContatto', 'idContatto');
    }
}
