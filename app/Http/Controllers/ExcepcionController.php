<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExcepcionRequest;
use App\Models\Employee;
use App\Models\Excepcion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExcepcionController extends Controller
{
    
    public function saveExcepcion(ExcepcionRequest $req){

        try{

            DB::beginTransaction();
            $excepcionNew=new Excepcion();
            $excepcionNew->fecha=$req->fechaExcepcion;
            $excepcionNew->hora_inicio=$req->horaInicio;
            $excepcionNew->hora_fin=$req->horaFin;
            $excepcionNew->motivo=$req->motivo;
            $excepcionNew->employee_id=$req->id;

            $excepcionNew->save();
            
            DB::commit();

            
            return response()->json([
                'message'=>'Excepción registrada con éxito',
                'excepcion'=>$excepcionNew
            ]);
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    
}
