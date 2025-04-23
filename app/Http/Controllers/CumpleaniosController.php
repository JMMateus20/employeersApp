<?php

namespace App\Http\Controllers;

use App\Mail\CumpleaniosMail;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class CumpleaniosController extends Controller
{
    public function getCumplesMesActual(){
        $mesActual=now()->format('m');
        Carbon::setLocale('es');
        return view('cumpleanios', ['registros'=>self::findCumplesByMes($mesActual), 'mesActual'=>$mesActual, 'mesFormatted'=>Carbon::now()->translatedFormat('F')]);
        
    }

    
    public function getCumplesPorMes($mes){
        return response()->json(['registros'=>self::findCumplesByMes($mes)]);
    }
    
    private function findCumplesByMes($mes){
        $registros=DB::select('SELECT e.id, e.nombre, e.fecha_nac, e.url_image, c.cargo FROM employees e INNER JOIN cargos c ON e.cargo_id=c.id WHERE MONTH(e.fecha_nac) = ? AND e.activo=1', [$mes]);
        return $registros;
    }


    
}
