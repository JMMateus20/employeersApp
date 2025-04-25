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
        <h2 style="margin: 0; color: red; margin-left: 100px">Eventos</h2>
        <button 
            id="btnMostrarModalAgregarEvento"
            style="padding: 0.5rem 1rem; background-color: #ff4d4d; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: bold; transition: background-color 0.2s ease;">
            Registrar evento
        </button>
    </div>
    <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 1rem; margin-top: 50px">
    
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
    <div class="modal fade" id="registrarEventoModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h1 class="modal-title fs-5" id="staticBackdropLabel">Registrar nuevo evento</h1>
            <button type="button" id="btnCloseModal" class="btn-close" aria-label="Close"></button>
            </div>
            <form id="formEvento">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="titulo" class="form-label">Título</label>
                        <input type="text" class="form-control" id="titulo" name="titulo" required>
                    </div>
            
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required></textarea>
                    </div>
                    <div class="row">
                        <div class="mb-3 col-md-4">
                            <label for="fecha" class="form-label">Fecha</label>
                            <input type="date" class="form-control" id="fecha" name="fecha" required>
                        </div>
                
                        <div class="mb-3 col-md-4">
                            <label for="hora_inicio" class="form-label">Hora de inicio</label>
                            <input type="time" class="form-control" id="hora_inicio" name="hora_inicio" required>
                        </div>
                
                        <div class="mb-3 col-md-4">
                            <label for="hora_fin" class="form-label">Hora de fin</label>
                            <input type="time" class="form-control" id="hora_fin" name="hora_fin" required>
                        </div>

                    </div>
                    <div class="mb-3">
                        <label for="asistentes" class="form-label">Seleccionar asistentes</label>
                        <select id="asistentes" name="asistentes[]" class="form-control" multiple="multiple" style="width: 100%">
                            @foreach ($employeers as $empleado)
                                <option value="{{ $empleado->id }}">{{ $empleado->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                </div>
                <div class="modal-footer">
                    
                    <button id="btnGuardarEvento" type="button" class="btn btn-primary">Registrar</button>
                </div>

            </form>
        </div>
        </div>
    </div>


    <div class="modal fade" id="modalDetalleEvento" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h1 class="modal-title fs-5" id="staticBackdropLabel">Detalle del evento</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                
            
                <div class="container">
                    <div class="row mb-3">
                        <div class="col-5 fw-bold">Titulo:</div>
                        <div class="col-7" id="eventoTitulo"></div>
                    </div>
                
                    <div class="row mb-3">
                        <div class="col-5 fw-bold">Descripción:</div>
                        <div class="col-7" id="eventoDescripcion"></div>
                    </div>
                
                    <div class="row mb-3">
                        <div class="col-5 fw-bold">Fecha:</div>
                        <div class="col-7" id="eventoFecha"></div>
                    </div>
                
                    <div class="row mb-3">
                        <div class="col-5 fw-bold">Hora de inicio:</div>
                        <div class="col-7 badge bg-primary" id="eventoHoraInicio"></div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-5 fw-bold">Hora fin:</div>
                        <div class="col-7 badge bg-primary" id="eventoHoraFin"></div>
                    </div>
                
                    
                </div>
    
                <div id="eventoAsistentes" class="mt-4"></div>
            </div>
            <div class="modal-footer" id="mfoot">
                <button class="btnEliminar btn btn-danger btn-sm me-2 rounded-circle"><i class="fa-solid fa-trash"></i></button>
            </div>
          </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endsection


<script>

    let eventos=@json($eventos);

    document.addEventListener('DOMContentLoaded', function() {
        
        $('#asistentes').select2({
            dropdownParent: $('#registrarEventoModal'),
            placeholder: 'Buscar y seleccionar empleados',
            allowClear: true
        });
        
        const formEvento = document.getElementById('formEvento');
        const modalEvento = document.getElementById('registrarEventoModal');
        const modalEventoInstance = new bootstrap.Modal(modalEvento);
        const modalDetalleEvento = document.getElementById('modalDetalleEvento');
        const modalDetalleEventoInstance = new bootstrap.Modal(modalDetalleEvento);

        dibujarEventos();

        document.getElementById('btnMostrarModalAgregarEvento').addEventListener('click', function(){
            abrirModalRegistrar(modalEventoInstance);
        });

        document.getElementById('contenedorCeldas').addEventListener('click', function (e) {
            const bloqueEvento = e.target.closest('.clickeable');
            if (bloqueEvento) {
                
                verDetalleEvento(bloqueEvento.dataset.id, modalDetalleEventoInstance);
            }
        });

        document.getElementById('btnGuardarEvento').addEventListener('click', function(){
            registrarEvento(formEvento, modalEventoInstance);
        })

        document.getElementById('btnCloseModal').addEventListener('click', function(){
            cerrarModal(formEvento, modalEventoInstance);
        });

        document.getElementById('mfoot').addEventListener('click', function(e){
            if (e.target.closest('.btnEliminar')) {
                const btn = e.target.closest('.btnEliminar');
                const id = btn.getAttribute('data-id');
                eliminar(id, modalDetalleEventoInstance);
            }
            
            
        });
        

    });

    function abrirModalRegistrar(modalEventoInstance){
        modalEventoInstance.show();
    }

    function dibujarEventos(){
        eventos.forEach(e => {
            const horarios={
                id: e.id,
                inicio: e.hora_inicio,
                fin: e.hora_fin,
                titulo: e.titulo
            };

            const fecha={
                fecha: e.fecha
            }
            dibujarColoreado(horarios, fecha, 'rgba(255, 0, 0, 0.5)');
            
            
        });
    }

    function cerrarModal(formEvento, modalEventoInstance){
        formEvento.reset();
        $('#asistentes').val(null).trigger('change');
        modalEventoInstance.hide();
    }

    async function registrarEvento(formEvento, modalEventoInstance){
        Swal.fire({
            title: 'Cargando...',
            html: 'Por favor espera un momento',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        const data={
            titulo: document.getElementById('titulo').value,
            descripcion: document.getElementById('descripcion').value,
            fecha: document.getElementById('fecha').value,
            hora_inicio: document.getElementById('hora_inicio').value,
            hora_fin: document.getElementById('hora_fin').value,
            asistentes: $('#asistentes').val()
        }

        try{
            const response=await fetch('/eventos/save', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(data)
            });

            const responseBody=await response.json();
            console.log(responseBody);


            if (!response.ok) {
                if (response.status===400) {
                    if (responseBody.errorMessage) {
                        Swal.fire('Error', responseBody.errorMessage, 'error');
                        return;
                    }
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
                
                eventos.push(responseBody.evento);
                limpiarCalendario();
                dibujarEventos();

                Swal.fire({
                    icon: 'success',
                    title: 'Exito!',
                    text: responseBody.message,
                    confirmButtonText: 'Aceptar'
                });

                cerrarModal(formEvento, modalEventoInstance);
            }
        }catch(error){
            console.log(error);
        }
    }

    async function verDetalleEvento(id, modalDetalleEventoInstance){
        try{
            const response=await fetch(`/eventos/find/${id}`);
            const responseBody=await response.json();

            console.log(responseBody);

            document.getElementById('eventoTitulo').textContent=responseBody.evento.titulo;
            document.getElementById('eventoDescripcion').textContent=responseBody.evento.descripcion;
            document.getElementById('eventoFecha').textContent=responseBody.evento.fecha;
            document.getElementById('eventoHoraInicio').textContent=responseBody.evento.hora_inicio;
            document.getElementById('eventoHoraFin').textContent=responseBody.evento.hora_fin;

            
            const contenedorAsistentes=document.getElementById('eventoAsistentes');
            contenedorAsistentes.innerHTML='';

            const titulo = document.createElement('h5');
            titulo.textContent = 'Asistentes';
            titulo.classList.add('mb-3');
            titulo.classList.add('text-center');

            contenedorAsistentes.appendChild(titulo);


            const encabezado = document.createElement('div');
            encabezado.classList.add('d-flex', 'justify-content-between', 'fw-bold', 'mb-1', 'px-2');
            encabezado.innerHTML = `
                <span>Nombre</span>
                <span>Cargo</span>
            `;
            contenedorAsistentes.appendChild(encabezado);

            const lista = document.createElement('ul');
            lista.classList.add('list-group');

            responseBody.asistentes.forEach(a => {
                const item = document.createElement('li');
                item.classList.add('list-group-item', 'd-flex', 'justify-content-between', 'align-items-center');

                

                contenedorAsistentes.appendChild(encabezado);
                const nombre = document.createElement('span');
                nombre.textContent = a.nombre;

                contenedorAsistentes.appendChild(encabezado);
                const cargo = document.createElement('span');
                cargo.textContent = a.cargo.cargo;

                

                item.appendChild(nombre);
                item.appendChild(cargo);

                lista.appendChild(item);
            });

            contenedorAsistentes.appendChild(lista);

            const boton = document.querySelector('.btnEliminar');
            boton.setAttribute('data-id', responseBody.evento.id);
            
            modalDetalleEventoInstance.show();
            
        }catch(error){
            console.log(error);
        }
    }

    async function eliminar(id, modalDetalleEventoInstance) {
        const result=await Swal.fire({
            title: "Estas seguro?",
            text: "No podrás revertir esta acción!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Confirmar"
        });
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Cargando...',
                html: 'Por favor espera un momento',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            try{
                const response=await fetch(`/eventos/delete/${id}`, {
                    method:'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                const responseBody=await response.json();

                if (response.ok) {
                    eventos=eventos.filter(e => e.id !== parseInt(id));
                    limpiarCalendario();
                    dibujarEventos();
                    Swal.fire({
                        title: "Eliminado!",
                        text: responseBody.message,
                        icon: "success"
                    });

                    modalDetalleEventoInstance.hide();
                    

                }
            }catch(error){
                console.log(error);
            }
        
        }
    }
</script>
