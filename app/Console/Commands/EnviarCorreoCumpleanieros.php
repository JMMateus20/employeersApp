<?php

namespace App\Console\Commands;

use App\Mail\CumpleaniosMail;
use App\Mail\FelicitacionMail;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

#[Schedule('everyMinute')]
class EnviarCorreoCumpleanieros extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:enviar-correo-cumpleanieros';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $fechaActual=Carbon::now('America/Bogota');
        
        $empleadosCumplenHoy=collect(DB::select('SELECT * FROM employees WHERE DAY(fecha_nac)=? AND MONTH(fecha_nac)=?', [$fechaActual->day, $fechaActual->month]));
        Log::info("Entro al command");
        if (empty($empleadosCumplenHoy)) {
            return;
        }

        Log::info("Empleados que cumplen hoy: " . $empleadosCumplenHoy->pluck('nombre')->implode(', '));
        $correosCumpleanieros = $empleadosCumplenHoy->pluck('correo')->toArray();

        $destinatarios=Employee::whereNotIn('correo', $correosCumpleanieros)
                    ->pluck('correo')->toArray();

        Log::info($destinatarios);

        Mail::to($destinatarios)->send(new CumpleaniosMail($empleadosCumplenHoy));
        Mail::to($correosCumpleanieros)->send(new FelicitacionMail());
    }

    
}
