<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Excepcion extends Model
{
    protected $table='excepciones';

    public $timestamps=true;
    protected $fillable = [
        'fecha',
        'hora_inicio',
        'hora_fin',
        'motivo',
        'employee_id'
    ];


    public function employeer(){
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }
}
