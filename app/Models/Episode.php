<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Episode extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
    'title',
    'episode_number',
    'season_number',
    'series_id'
];

}
