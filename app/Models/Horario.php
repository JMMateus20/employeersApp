<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    protected $table='horarios';
    public $timestamps=true;

    protected $fillable = [
        'dia_id',
        'hora_inicio',
        'hora_fin',
        'employee_id'
    ];

    public function employee(){
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

    public function dia(){
        return $this->belongsTo(Dia::class, 'dia_id', 'id');
    }
}
