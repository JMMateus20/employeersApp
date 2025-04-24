<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $table='employees';

    public $timestamps=true;
    protected $fillable = [
        'nombre',
        'correo',
        'fecha_ingreso',
        'fecha_nac',
        'activo',
        'url_image',
        'cargo_id'
    ];

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($employee) {
            $employee->horarios()->delete();
        });
    }


    public function horarios(){
        return $this->hasMany(Horario::class, 'employee_id', 'id');
    }

    public function excepciones(){
        return $this->hasMany(Excepcion::class, 'employee_id', 'id');
    }

    public function eventos(){
        return $this->belongsToMany(Evento::class, 'employee_eventos', 'employee_id', 'evento_id');
    }

    public function cargo(){
        return $this->belongsTo(Cargo::class, 'cargo_id', 'id');
    }


}
