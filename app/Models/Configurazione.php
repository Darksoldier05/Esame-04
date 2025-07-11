<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Configurazione extends Model
{
    protected $table = 'configurazioni';
    protected $primaryKey = 'idConfigurazione';
    public $timestamps = true;

    /**
     * Legge un valore dalla tabella configurazioni.
     *
     * @param string $chiave
     * @return mixed|null
     */
    public static function leggiValore($chiave)
    {
        $record = self::where('chiave', $chiave)->first();
        return $record ? $record->valore : null;
    }
}
