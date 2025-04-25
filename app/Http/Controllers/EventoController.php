<?php

namespace App\Http\Controllers;

use App\Http\Requests\EventoRequest;
use App\Models\Evento;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventoController extends Controller
{
    public function index(){

        $startOfWeek = Carbon::now()->startOfWeek(Carbon::MONDAY);
        $days = [];

        for ($i = 0; $i < 7; $i++) {
            $days[] = $startOfWeek->copy()->addDays($i);
        }

        return view('eventos', ['days'=>$days, 'eventos'=>Evento::all(), 'employeers'=>Employee::all()]);
    }

    public function save(EventoRequest $req){
        $existentes=DB::select('SELECT * FROM eventos WHERE fecha = ? AND hora_inicio = ? AND hora_fin = ?', [$req->fecha, $req->hora_inicio, $req->hora_fin]);

        if (!empty($existentes)) {
            return response()->json(['errorMessage'=>'Ya existe un evento registrado en este horario'], 400);
        }
        try{
            DB::beginTransaction();
            $eventoNew=Evento::create([
                'titulo'=>$req->titulo,
                'descripcion'=>$req->descripcion,
                'fecha'=>$req->fecha,
                'hora_inicio'=>$req->hora_inicio,
                'hora_fin'=>$req->hora_fin
            ]);
            $eventoNew->asistentes()->attach($req->asistentes);
            DB::commit();

            return response()->json([
                'message'=>'Evento registrado con éxito',
                'evento'=>$eventoNew
            ], 200);
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json(['error'=>$e->getMessage()], 500);
        }

    }

    public function find($id){
        $eventoBD=Evento::with('asistentes.cargo')->find($id);
        return response()->json([
            'evento'=>$eventoBD,
            'asistentes'=>$eventoBD->asistentes
        ], 200);
    }

    public function delete($id){
        $eventoBD=Evento::find($id);
        try{
            DB::beginTransaction();
            $eventoBD->asistentes()->detach();
            $eventoBD->delete();
            DB::commit();
            return response()->json([
                'message'=>'Evento eliminado con éxito'
            ], 200);
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json([
                'error'=>$e->getMessage()
            ], 500);
        }
        
    }
}
