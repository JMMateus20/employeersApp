<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HorarioController extends Controller
{
    public function getHorario($id){

        $startOfWeek = Carbon::now()->startOfWeek(Carbon::MONDAY);
        $days = [];

        for ($i = 0; $i < 7; $i++) {
            $days[] = $startOfWeek->copy()->addDays($i);
        }

        $nombreDias = [];

        foreach ($days as $day) {
            $nombreDias[] = ucfirst($day->locale('es')->isoFormat('dddd'));
        }

        $horariosEmployeer=DB::table('horarios')
        ->join('dias', 'horarios.dia_id', '=', 'dias.id')
        ->where('horarios.employee_id', '=', $id)
        ->select('dias.dia as dia', 'horarios.hora_inicio', 'horarios.hora_fin')
        ->get();
        
        $horarios=[
            'Lunes'=>[],
            'Martes'=>[],
            'Miércoles'=>[],
            'Jueves'=>[],
            'Viernes'=>[],
            'Sabado'=>[]
        ];

        

        $dias=['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sabado'];

        foreach ($dias as $key => $dia) {
            foreach ($horariosEmployeer as $key => $h) {
                if ($h->dia===self::quitarTildes($dia)) {
                    $horarios[$dia][]=[
                        'inicio'=>$h->hora_inicio,
                        'fin'=>$h->hora_fin
                    ];
                }
            }
        }

        $employeer=Employee::find($id);
        $excepciones=[];
        foreach ($employeer->excepciones as $key => $e) {
            $excepciones[]=[
                'id'=>$e->id,
                'inicio'=>$e->hora_inicio,
                'fin'=>$e->hora_fin,
                'fecha'=>$e->fecha,
                'motivo'=>$e->motivo
            ];
        }


        return view('horarios', compact('days', 'horarios', 'nombreDias', 'excepciones'));

    }


    private function quitarTildes($cadena) {
        $acentos = [
            'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u',
            'Á' => 'A', 'É' => 'E', 'Í' => 'I', 'Ó' => 'O', 'Ú' => 'U',
        ];
        return strtr($cadena, $acentos);
    }
}
