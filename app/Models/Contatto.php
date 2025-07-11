<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Contatto extends Authenticatable implements JWTSubject
{
    use HasFactory, SoftDeletes, HasRoles;

    protected $table = 'contatti';
    protected $primaryKey = 'idContatto';
    public $incrementing = true;
    protected $keyType = 'int';

    // Queste due funzioni sono necessarie per JWT
    public function getJWTIdentifier()
    {
        return $this->idContatto;
    }

    public function getJWTCustomClaims()
    {
        return ['idContatto' => $this->idContatto];
    }

    protected $with = ['recapiti', 'indirizzi', 'crediti'];

    protected $fillable = [
        'idStatoContatto',
        'nome',
        'cognome',
        'sesso',
        'codiceFiscale',
        'partitaIva',
        'cittadinanza',
        'idNazioneNascita',
        'cittaNascita',
        'provinciaNascita',
        'dataNascita',
        'archiviato',
        'created_by',
        'updated_by',
    ];

    // Relazioni
    public function crediti()
    {
        return $this->hasOne(ContattoCredito::class, 'idContatto', 'idContatto');
    }

    public function recapiti()
    {
        return $this->hasMany(Recapito::class, 'idContatto', 'idContatto');
    }

    public function indirizzi()
    {
        return $this->hasMany(Indirizzo::class, 'idContatto', 'idContatto');
    }

}
