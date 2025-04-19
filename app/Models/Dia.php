<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dia extends Model
{
    protected $table='dias';
    public $timestamps=true;

    protected $fillable = [
        'dia'
        
    ];
}
