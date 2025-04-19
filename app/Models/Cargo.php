<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cargo extends Model
{
    protected $table='cargos';

    public $timestamps=true;
    protected $fillable = [
        'cargo'
    ];
}
