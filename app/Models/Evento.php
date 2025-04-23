<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    protected $table='eventos';
    public $timestamps=true;
    protected $fillable = [
        'titulo',
        'descripcion',
        'fecha_inicio',
        'fecha_fin'
    ];


    public function asistentes(){
        return $this->belongsToMany(Employee::class, 'employee_eventos', 'evento_id', 'employee_id');
    }
}
