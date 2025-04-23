@extends('welcome')
@section('styles')
<style>
    

    .calendario {
        display: grid;
        grid-template-columns: 80px repeat(7, 1fr);
    }
    .hora {
        height: 40px;
        border-bottom: 1px solid #ddd;
        padding-left: 4px;
    }
    .celda {
        height: 40px;
        border: 1px solid #eee;
        position: relative;
        text-align: center;
    }
    .resaltado {
        position: absolute;
        left: 0;
        right: 0;
        border-radius: 4px;
        z-index: 1;
    }

    .resaltado.clickeable {
        cursor: pointer;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .resaltado.clickeable:hover {
        transform: scale(1.05) translateY(-2px);
        box-shadow: 0 4px 10px rgba(255, 0, 0, 0.3);
    }
        
</style>
@endsection
@section('html')

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
    <h2 style="margin: 0; color: navy; margin-left: 100px">Horarios</h2>
    <button 
        id="btnMostrarModalAgregarExcepcion"
        style="padding: 0.5rem 1rem; background-color: #ff4d4d; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: bold; transition: background-color 0.2s ease;">
        Añadir excepción
    </button>
</div>


<div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 1rem;">
    <!-- Leyenda lateral -->
    <div style="min-width: 150px; font-size: 14px;">
        <h4 style="margin-bottom: 0.5rem;">Guía:</h4>
        <div style="display: flex; align-items: center; margin-bottom: 0.25rem;">
            <div style="width: 20px; height: 20px; background-color: rgba(0, 128, 255, 0.5); border-radius: 4px; margin-right: 8px;"></div>
            <span>Horario ordinario</span>
        </div>
        <div style="display: flex; align-items: center;">
            <div style="width: 20px; height: 20px; background-color: rgba(255, 0, 0, 0.5); border-radius: 4px; margin-right: 8px;"></div>
            <span>Excepción</span>
        </div>
    </div>
    <div id="contenedorCeldas">

        <div class="calendario">
            <div></div>
            @foreach ($days as $day)
                <div style="text-align:center; font-weight:bold;">
                    {{ ucfirst($day->locale('es')->isoFormat('dddd D [de] MMMM')) }}
                </div>
            @endforeach
        
            @for ($h = 6; $h <= 22; $h++)
                <div class="hora">{{ sprintf('%02d:00', $h) }}</div>
                
                @foreach ($days as $day)
            
                    <div class="celda" id="celda-{{ $day->format('Y-m-d') }}-{{ $h }}"></div>
                
                @endforeach
                
            @endfor
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="modalExcepcionForm" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Registrar excepción</h1>
                <button type="button" class="btn-close" id="btnCloseModalFormExcepcion"></button>
            </div>
            
            <form id="formExcepcion">
                <div class="modal-body">
                    
                    <div class="mb-3">
                        <label for="fechaExcepcion" class="form-label">Fecha de la excepción</label>
                        <input type="date" class="form-control" id="fechaExcepcion" name="fechaExcepcion">
                    </div>

                    <div class="mb-3">
                        <label for="horaInicio" class="form-label">Hora inicio</label>
                        <input type="time" class="form-control" id="horaInicio" name="horaInicio">
                    </div>
        
                    <div class="mb-3">
                        <label for="horaFin" class="form-label">Hora fin</label>
                        <input type="time" class="form-control" id="horaFin" name="horaFin">
                    </div>
        
        
                    <div class="mb-3">
                        <label for="motivo" class="form-label">Motivo de la excepción</label>
                        <input type="text" class="form-control" id="motivo" name="motivo" placeholder="Algún motivo corto...">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="btnGuardarExcepcion">Registrar</button>
                </div>
            </form>
            
        </div>
    </div>
</div>



<!-- Modal -->
<div class="modal fade" id="modalVerExcepcion" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content shadow-lg border-0 rounded-4">
      <div class="modal-header bg-primary text-white rounded-top-4">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">Detalle de excepción</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        <div class="mb-3">
            <h6 class="fw-bold mb-1">📅 Fecha</h6>
            <p class="form-control-plaintext" id="campoFecha"></p>
        </div>
        <div class="mb-3">
            <h6 class="fw-bold mb-1">🕒 Hora de inicio</h6>
            <p class="form-control-plaintext" id="campoHoraInicio"></p>
        </div>
        <div class="mb-3">
            <h6 class="fw-bold mb-1">🕕 Hora de fin</h6>
            <p class="form-control-plaintext" id="campoHoraFin"></p>
        </div>
        <div class="mb-3">
            <h6 class="fw-bold mb-1">✏️ Motivo</h6>
            <p class="form-control-plaintext" id="campoMotivo"></p>
        </div>
    </div>
  </div>
</div>


@endsection

<script>
    function obtenerLunes(fecha) {
        const dia = fecha.getDay(); // 0 = domingo, 1 = lunes, ..., 6 = sábado
        const diferencia = dia === 0 ? -6 : 1 - dia;
        const lunes = new Date(fecha);
        lunes.setDate(fecha.getDate() + diferencia);
        return lunes;
    }

    const dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sabado'];

    let fechasSemana = [];
    let lunes = obtenerLunes(new Date());

    for (let i = 0; i < 6; i++) {
        const fecha = new Date(lunes);
        fecha.setDate(lunes.getDate() + i);

        const año = fecha.getFullYear();
        const mes = String(fecha.getMonth() + 1).padStart(2, '0');
        const dia = String(fecha.getDate()).padStart(2, '0');
        const fechaFormateada = `${año}-${mes}-${dia}`;

        fechasSemana.push({
            nombre: dias[i],
            fecha: fechaFormateada
        });
    }


    const horarios=@json($horarios);

    let excepciones=@json($excepciones);
    /*const excepciones=[
        {id: 1, inicio: '09:30:00', fin: '10:30:00', fecha: '2025-04-19'},
        {id:2, inicio: '08:00:00', fin: '10:30:00', fecha: '2025-04-17'}
    ]*/

    
    function resaltarHorarios() {

        fechasSemana.forEach(fecha => {
            const bloques = horarios[fecha.nombre];

            if (!bloques) return;

            bloques.forEach(horario => {
                dibujarColoreado(horario, fecha, 'rgba(0, 128, 255, 0.3)');
                
            });
        });
    }


    function resaltarExcepciones(){
        excepciones.forEach(e => {
            fechasSemana.forEach(fecha => {
                if (e.fecha === fecha.fecha) {
                    dibujarColoreado(e, fecha, 'rgba(255, 0, 0, 0.5)');
                    
                    
                }
                
            });
        });
    }


    function abrirModalFormExcepcion(modalExcepcionInstance){
        modalExcepcionInstance.show();
    }

    function cerrarModalFormExcepcion(modalExcepcionInstance, formExcepcion){
        formExcepcion.reset();
        modalExcepcionInstance.hide();

    }

    async function saveExcepcion(modalExcepcionInstance, formExcepcion){
        Swal.fire({
            title: 'Cargando...',
            html: 'Por favor espera un momento',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        const pathSegments = window.location.pathname.split('/');
        const id = pathSegments[pathSegments.length - 1];
        const data={
            id,
            fechaExcepcion: document.getElementById('fechaExcepcion').value,
            horaInicio: document.getElementById('horaInicio').value,
            horaFin: document.getElementById('horaFin').value,
            motivo: document.getElementById('motivo').value
        };
        try{
            const response=await fetch('/excepciones/save', {
                method: "POST",
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(data)
            });

            const responseBody=await response.json();

            console.log(responseBody);
            if (!response.ok) {
                if (response.status === 400) {
                        
                    console.error("Errores de validación:", responseBody.errors);
                    const mensajes = Object.values(responseBody.errors)
                        .flat()
                        .map(msg => `• ${msg}`)
                        .join('\n');
    
                    Swal.fire({
                        icon: 'error',
                        title: 'Errores de validación',
                        text: 'Revisa los siguientes errores:',
                        html: `<pre style="text-align:left;">${mensajes}</pre>`,
                        confirmButtonText: 'Aceptar'
                    });
                }
                
            }else{
                const excepcionNew={
                    id: responseBody.excepcion.id,
                    inicio: responseBody.excepcion.hora_inicio,
                    fin: responseBody.excepcion.hora_fin,
                    fecha: responseBody.excepcion.fecha,
                    motivo: responseBody.excepcion.motivo
                };
                redibujarExcepciones(excepcionNew);
                Swal.fire({
                    icon: 'success',
                    title: 'Exito!',
                    text: responseBody.message,
                    confirmButtonText: 'Aceptar'
                });
                
                cerrarModalFormExcepcion(modalExcepcionInstance, formExcepcion);
                
            }

        }catch(error){
            console.log(error);
        }


    }

    function redibujarExcepciones(excepcion) {
        excepciones.push(excepcion);
        fechasSemana.forEach(fecha => {
            for (let h = 6; h <= 22; h++) {
                const celda = document.getElementById(`celda-${fecha.fecha}-${h}`);
                if (celda) {
                    celda.innerHTML = '';
                }
            }
        });
        resaltarHorarios();
        resaltarExcepciones();
    }

    async function verDetalle(id, modalDetalleInstance){
        try{
            const response=await fetch(`/excepciones/find/${id}`);

            const responseBody=await response.json();

            console.log(responseBody);

            if (response.ok) {
                document.getElementById("campoFecha").textContent = responseBody.excepcion.fecha;
                document.getElementById("campoHoraInicio").textContent = responseBody.excepcion.hora_inicio;
                document.getElementById("campoHoraFin").textContent = responseBody.excepcion.hora_fin;
                document.getElementById("campoMotivo").textContent = responseBody.excepcion.motivo;
                
                modalDetalleInstance.show();
            }


        }catch(error){
            console.log(error);
        }
    }

    

    
    document.addEventListener('DOMContentLoaded', function() {

        const modalExcepcion=document.getElementById('modalExcepcionForm');
        const modalExcepcionInstance = new bootstrap.Modal(modalExcepcion);
        const formExcepcion=document.getElementById('formExcepcion');

        const modalDetalle=document.getElementById('modalVerExcepcion');
        const modalDetalleInstance = new bootstrap.Modal(modalDetalle);


        resaltarHorarios();
        resaltarExcepciones();

        document.getElementById('contenedorCeldas').addEventListener('click', function (e) {
            const bloqueExcepcion = e.target.closest('.clickeable');
            if (bloqueExcepcion) {
                
                verDetalle(bloqueExcepcion.dataset.id, modalDetalleInstance);
            }
        });

        

        document.getElementById('btnMostrarModalAgregarExcepcion').addEventListener('click', () => {
            abrirModalFormExcepcion(modalExcepcionInstance);
        });

        document.getElementById('btnCloseModalFormExcepcion').addEventListener('click', () => {
            cerrarModalFormExcepcion(modalExcepcionInstance, formExcepcion);
        });

        document.getElementById('btnGuardarExcepcion').addEventListener('click', () => {
            saveExcepcion(modalExcepcionInstance, formExcepcion);
        });
        
        
    });
</script>