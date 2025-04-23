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
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            ...
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary">Understood</button>
            </div>
        </div>
        </div>
    </div>

@endsection


<script>
    let eventos=@json($eventos);

    document.addEventListener('DOMContentLoaded', function() {

        const modalEvento=document.getElementById('registrarEventoModal');
        const modalEventoInstance = new bootstrap.Modal(modalEvento);

        dibujarEventos();

        document.getElementById('btnMostrarModalAgregarEvento').addEventListener('click', function(){
            abrirModalRegistrar(modalEventoInstance);
        });

        document.getElementById('contenedorCeldas').addEventListener('click', function (e) {
            const bloqueEvento = e.target.closest('.clickeable');
            if (bloqueEvento) {
                
                console.log(bloqueEvento.dataset.id);
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
                fin: e.hora_fin
            };

            const fecha={
                fecha: e.fecha
            }
            dibujarColoreado(horarios, fecha, 'rgba(255, 0, 0, 0.5)');
            
            
        });
    }
</script>
