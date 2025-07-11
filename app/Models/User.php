<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // Usa il modello auth di Laravel!
use Illuminate\Notifications\Notifiable;

// Se usi Spatie/Permission
// use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    // Se usi Spatie/Permission decommenta la riga sotto
    // use HasRoles;

    // Nome della tabella (opzionale se segue la convenzione)
    // protected $table = 'users';

    /**
     * I campi che si possono riempire tramite Mass Assignment.
     */
    protected $fillable = [
        'name',        // oppure 'nome' se preferisci
        'email',
        'password',
        // aggiungi altri campi custom qui
    ];

    /**
     * I campi da nascondere quando serializzi il modello (ad es. in JSON).
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * I campi da convertire in tipo "cast".
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Se usi mutator per criptare password
    public function setPasswordAttribute($value)
    {
        // Cambia hash se vuoi altro algoritmo
        $this->attributes['password'] = bcrypt($value);
    }
}

