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


function setLinearGradient(minuto, color1, color2, defaultColor){
       
    let porcentajeResaltado='';

    if (minuto>=0 && minuto<30) {
        if (minuto===0) {
            return defaultColor;
        }
        porcentajeResaltado='15%';
        
    }else if (minuto===30) {
        porcentajeResaltado='50%';
    }else {
        porcentajeResaltado='75%';
    }


    return `linear-gradient(to bottom, ${color1} 0%, ${color1} ${porcentajeResaltado}, ${color2} ${porcentajeResaltado}, ${color2} 100%)`;
        
    
        
    
}

function dibujarColoreado(horario, fecha, defaultColor){
    const horaInicio=parseInt(horario.inicio.split(':')[0], 10);
    const minutoInicio=parseInt(horario.inicio.split(':')[1], 10);
    
    const horaFin=parseInt(horario.fin.split(':')[0], 10);
    const minutoFin=parseInt(horario.fin.split(':')[1], 10);
    for (let h = horaInicio; h <= horaFin; h++) {
        const celda = document.getElementById(`celda-${fecha.fecha}-${h}`);
        if (celda) {
            
            let gradiente = defaultColor;
           
            if (h==horaInicio) {
                gradiente= setLinearGradient(minutoInicio, 'transparent', defaultColor, defaultColor);
                
            }

            if (h==horaFin) {
                if (minutoFin==0) {
                    continue;
                }
                gradiente=setLinearGradient(minutoFin, defaultColor, 'transparent', defaultColor);
                
            }

            

            const bloque = document.createElement('div');
            bloque.style.background = gradiente;
            bloque.classList.add('resaltado');
            bloque.style.top = '2px';
            bloque.style.bottom = '2px';
            bloque.textContent = '';

            //verificar si es excepcion o evento para poder dar click sobre el
            if (defaultColor === 'rgba(255, 0, 0, 0.5)') {
                bloque.classList.add('clickeable');
                bloque.dataset.id = horario.id;
            }

            celda.appendChild(bloque);
        }
    }
}

function limpiarCalendario() {
        
    fechasSemana.forEach(fecha => {
        for (let h = 6; h <= 22; h++) {
            const celda = document.getElementById(`celda-${fecha.fecha}-${h}`);
            if (celda) {
                celda.innerHTML = '';
            }
        }
    });
    
}

window.dibujarColoreado = dibujarColoreado;
window.limpiarCalendario = limpiarCalendario;
window.fechasSemana=fechasSemana;
