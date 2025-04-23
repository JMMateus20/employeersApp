@extends('welcome')
@section('styles')
    <style>
        .cumple-wrapper {
            max-width: 900px;
            margin: 0 auto;
            padding: 2rem;
        }

        .mes-header {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .flecha {
            font-size: 1.5rem;
            background: none;
            border: none;
            cursor: pointer;
        }

        .flecha:hover{
            transform: scale(1.05) translateY(-2px);
        }

        .tarjetas-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1.5rem;
        }

        .card-cumple {
            background: #fef3c7;
            padding: 1rem;
            border-radius: 1rem;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: transform 0.2s ease;
        }

        .card-cumple:hover {
            transform: scale(1.03);
        }

        .card-cumple img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 0.5rem;
        }


    </style>

@endsection

@section('html')
    <div class="cumple-wrapper">
        <div class="mes-header">
            <button id="btnMesAnterior" onclick="retrocederMes()" class="flecha">&lt;</button>
            <h2 id="mesActual">{{ Str::ucfirst($mesFormatted) }}</h2>
            <button id="btnMesSiguiente" onclick="avanzarMes()" class="flecha">&gt;</button>
        </div>

        <div class="tarjetas-container" id="contenedorCumples">
            @if (count($registros)>0)
                @foreach ($registros as $r)
                
                    <div class="card-cumple">
                        <img src="{{ asset('storage/' . $r->url_image) }}" alt="Imagen Empleado">
                        
                        <h4>{{ $r->nombre }}</h4>
                        <p>{{ \Carbon\Carbon::parse($r->fecha_nac)->translatedFormat('d \d\e F') }}</p>
                        <p>{{ $r->cargo }}</p>
                        <span style="font-size: 1.5rem;">🎂</span>
                    </div>
                @endforeach
            @else
                <div style="display: flex; flex-direction: column; justify-content: center; align-items: center; height: 60vh; text-align: center; grid-column: 1 / -1;">
                    <h2 style="font-size: 2rem; color: #888;">
                        🎉 No hay celebración de cumpleaños este mes 🎉
                    </h2>
                    <p style="font-size: 1.2rem; color: #aaa;">¡Vuelve el próximo mes para celebrar! 🥳</p>
                </div>
            @endif
            
        </div>
    </div>


@endsection

<script>
    
    const meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                   'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
    let mesActual=parseInt(@json($mesActual));


    function formatearMes(mes){
        return (mes<10) ? '0'+mes : mes;
    }

    function avanzarMes(){
        mesActual++;
        if (mesActual<=12) {
            buscarCumples(formatearMes(mesActual));

        }
    }

    function retrocederMes(){
        mesActual--;
        if (mesActual>0) {
            buscarCumples(formatearMes(mesActual));
        }
    }
    
    async function buscarCumples(mes){
        Swal.fire({
            title: 'Cargando...',
            html: 'Cargando contenido...',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        
        const btnSiguiente=document.getElementById('btnMesSiguiente');
        const btnAnterior=document.getElementById('btnMesAnterior');

        
        try{
            const response=await fetch(`/cumpleanios/getCumples/${mes}`);
            const responseBody=await response.json();

            if (response.ok) {
                
                console.log('mes Actual: ' + mesActual);
                document.getElementById('mesActual').innerText=meses[parseInt(mesActual)-1];
                const contenedor=document.getElementById('contenedorCumples');
                contenedor.innerHTML='';
    
                if (responseBody.registros.length>0) {
                    
                    responseBody.registros.forEach(r => {
                        
        
                        const card = document.createElement('div');
                        card.classList.add('card-cumple');
        
                        card.innerHTML = `
                            <img src="/storage/${r.url_image}" alt="Imagen Empleado">
                            <h4>${ r.nombre }</h4>
                            <p>${ formatearFecha(r) }</p>
                            <p>${ r.cargo }</p>
                            <span style="font-size: 1.5rem;">🎂</span>
                        `;
        
                        contenedor.appendChild(card);
                    });
                }else{
                    const mensajeDiv = document.createElement('div');
                    mensajeDiv.style.display = 'flex';
                    mensajeDiv.style.flexDirection = 'column';
                    mensajeDiv.style.justifyContent = 'center';
                    mensajeDiv.style.alignItems = 'center';
                    mensajeDiv.style.height = '60vh';
                    mensajeDiv.style.textAlign = 'center';
                    mensajeDiv.style.gridColumn = '1 / -1';
    
                    const h2 = document.createElement('h2');
                    h2.textContent = '🎉 No hay celebración de cumpleaños este mes 🎉';
                    h2.style.fontSize = '2rem';
                    h2.style.color = '#888';
    
                    const p = document.createElement('p');
                    p.textContent = '¡Vuelve el próximo mes para celebrar! 🥳';
                    p.style.fontSize = '1.2rem';
                    p.style.color = '#aaa';
    
                    mensajeDiv.appendChild(h2);
                    mensajeDiv.appendChild(p);
    
                    contenedor.appendChild(mensajeDiv);
                }

                if (btnSiguiente.disabled) {
                    setHabilitadoUOculto(btnSiguiente, false, 'visible');
                }

                if (btnAnterior.disabled) {
                    setHabilitadoUOculto(btnAnterior, false, 'visible');
                }

                
                if (mes==12) {
                    setHabilitadoUOculto(btnSiguiente, true, 'hidden');
                }
                if (mes==1) {
                    setHabilitadoUOculto(btnAnterior, true, 'hidden');
                }

                Swal.close();
            }


        }catch(error){
            console.log(error);
        }
    }

    function setHabilitadoUOculto(btn, disabled, visibility){
        btn.disabled=disabled;
        btn.style.visibility=visibility;
    }

    function formatearFecha(employeer){
        const fechaCumple=new Date(employeer.fecha_nac + 'T00:00:00Z');
        const dia=fechaCumple.getUTCDate();
        const mesNombre=meses[fechaCumple.getUTCMonth()];

        return `${dia} de ${mesNombre}`;
    }
</script>