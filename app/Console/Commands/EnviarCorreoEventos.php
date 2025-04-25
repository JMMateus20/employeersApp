<?php

namespace App\Console\Commands;

use App\Mail\EventoMail;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EnviarCorreoEventos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:enviar-correo-eventos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enviar correo recordatorio de los eventos próximos a realizarse';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $fechaActual=Carbon::now('America/Bogota');
        Log::info($fechaActual);
        $eventosProximos=collect(DB::select('SELECT e.id as evento_id, e.titulo, e.fecha, e.hora_inicio, e.hora_fin, s.correo, s.id as asistente_id FROM eventos e INNER JOIN employee_eventos m ON e.id=m.evento_id INNER JOIN employees s ON m.employee_id=s.id WHERE (e.fecha = ? OR e.fecha = ?) ORDER BY e.fecha', 
            [$fechaActual->copy()->addDay()->toDateString(), $fechaActual->toDateString()]));

        if (empty($eventosProximos)) {
            return;
        }
        $eventosAgrupados=$eventosProximos->groupBy('asistente_id');
        
        
        foreach ($eventosAgrupados as $asistenteId => $eventos) {
            Log::info($eventos);

            $correo = $eventos->first()->correo;
            Mail::to($correo)->send(new EventoMail($eventos));
        }


    }
}
