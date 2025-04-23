@extends('welcome')
@section('html')

    <h1 class="text-center mb-4">Listado de empleados</h1>

    <div class="d-flex justify-content-end mb-3">
        <button type="button" class="btn btn-primary" id="btnMostrarModalForm">
            Registrar empleado
        </button>

    </div>

    <div class="d-flex justify-content-end mb-3">
        <input type="text" id="busquedaEmpleado" class="form-control w-25" placeholder="Buscar por nombre..." value="{{ isset($termino) ? $termino : '' }}">
    </div>
    <table id="empleadosTable" class="table">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Nombre</th>
            <th scope="col">Correo</th>
            <th scope="col">Cargo</th>
            <th scope="col">Accion</th>
        </tr>
        </thead>
        <tbody id="employeeTableBody">
            @if (count($employees)>0)
                @foreach ($employees as $indice=>$employee)
                <tr>
                    <th scope="row">{{ $indice+1 }}</th>
                    <td>{{ $employee->nombre }}</td>
                    <td>{{ $employee->correo }}</td>
                    <td>{{ $employee->cargo }}</td>
                    <td>
                        <button data-id="{{ $employee->id }}" class="btnEditar btn btn-primary btn-sm me-2 rounded-circle"><i class="fa-solid fa-user-pen"></i></button>
                        <button data-id="{{ $employee->id }}" class="btnEliminar btn btn-danger btn-sm me-2 rounded-circle"><i class="fa-solid fa-trash"></i></button>
                        <a href="{{ route('horarios.index', $employee->id) }}" class="btn btn-primary btn-sm me-2 rounded-circle">
                            <i class="fa-solid fa-calendar"></i>
                        </a>
                        <button data-id="{{ $employee->id }}" class="btnVer btn btn-primary btn-sm me-2 rounded-circle"><i class="fa-solid fa-eye"></i></button>
                    </td>
                    <!--<img src="{{ asset('storage/' . $employee->url_image) }}" alt="Imagen subida" class="img-fluid rounded shadow" style="max-width: 300px;">-->
                </tr>
                @endforeach
            @endif
        </tbody>
        
    </table>
    <div id="paginator">
        
        <div class="d-flex justify-content-center">
            {{ $employees->links() }}
        </div>
            
       

    </div>

   
    
    <!-- Modal -->
    <div class="modal fade" id="modalEmployeeForm" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
        <div class="modal-content rounded-4 shadow-lg">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="labelModalTitle"></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="btnCerrarModal"></button>
            </div>
            <form id="formEmployee" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="number" id="idEmployee" name="idEmployee" hidden>
                
                    <div class="row">
                        <!-- Nombre -->
                        <div class="mb-3 col-md-6">
                            <label for="nombre" class="form-label">Nombre completo</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Juan Pérez" required>
                        </div>
            
                        <!-- Correo -->
                        <div class="mb-3 col-md-6">
                            <label for="correo" class="form-label">Correo electrónico</label>
                            <input type="email" class="form-control" id="correo" name="correo" placeholder="correo@ejemplo.com" required>
                        </div>
                    
                    </div>
                    <div class="row">
                        <!-- Fecha de ingreso -->
                        <div class="mb-3 col-md-6">
                            <label for="fechaIngreso" class="form-label">Fecha de ingreso</label>
                            <input type="date" class="form-control" id="fechaIngreso" name="fechaIngreso" required>
                        </div>
            
                        <!-- Fecha de nacimiento -->
                        <div class="mb-3 col-md-6">
                            <label for="fechaNacimiento" class="form-label">Fecha de nacimiento</label>
                            <input type="date" class="form-control" id="fechaNacimiento" name="fechaNacimiento" required>
                        </div>
                    
                    </div>
        
                    <div class="row">

                        <!-- Campo (Select) -->
                        <div class="mb-3 col-md-6">
                            <label for="campo" class="form-label">Cargo</label>
                            <select class="form-select" id="cargo" name="cargo" required>
                                @foreach ($cargosSelect as $c)
                                    <option value="{{ $c->id }}">{{ $c->cargo }}</option>
                                @endforeach
                                
                            </select>
                        </div>
    
                        <div class="mb-3 col-md-6">
                            <label for="imagen" class="form-label">Foto de perfil</label>
                            <input type="file" class="form-control" id="imagen" name="imagen" required>
                        </div>
                    </div>

                    
          
                    @foreach(['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'] as $dia) 
                        <div class="mb-3">
                            <label class="form-label">{{ $dia }}</label>
                            <div class="row fw-bold text-secondary mb-1">
                                <div class="col-5">Hora inicio</div>
                                <div class="col-5">Hora fin</div>
                                <div class="col-2"></div>
                            </div>
                            <div id="contenedor-{{ strtolower($dia) }}">
                            <div class="row align-items-center mb-2 horario-item">
                                <div class="col-5">
                                <input type="time" class="form-control" name="{{ strtolower($dia) }}_inicio[]">
                                </div>
                                <div class="col-5">
                                <input type="time" class="form-control" name="{{ strtolower($dia) }}_fin[]">
                                </div>
                                <div class="col-2">
                                <button type="button" class="btn btn-danger btn-sm btnEliminarHorario">&times;</button>
                                </div>
                            </div>
                            </div>
                            <button type="button" class="btn btn-secondary btn-sm" onclick="agregarHorario('{{ strtolower($dia) }}')">Agregar horario</button>
                        </div>
                    @endforeach
            
                        
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="btnGuardar"></button>
                </div>
            </form>
        </div>
        </div>
    </div>

    
  
  <!-- Modal -->
  <div class="modal fade" id="modalDetalleEmployeer" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="staticBackdropLabel">Detalle empleado</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="text-center mb-4">
                <img id="empleadoImagen" src="" alt="Foto del empleado" class="rounded-circle shadow" style="width: 150px; height: 150px; object-fit: cover;">
            </div>
        
            <div class="container">
                <div class="row mb-3">
                    <div class="col-5 fw-bold">Nombre:</div>
                    <div class="col-7" id="empleadoNombre"></div>
                </div>
            
                <div class="row mb-3">
                    <div class="col-5 fw-bold">Correo:</div>
                    <div class="col-7" id="empleadoCorreo"></div>
                </div>
            
                <div class="row mb-3">
                    <div class="col-5 fw-bold">Fecha de ingreso:</div>
                    <div class="col-7" id="empleadoIngreso"></div>
                </div>
            
                <div class="row mb-3">
                    <div class="col-5 fw-bold">Fecha de nacimiento:</div>
                    <div class="col-7" id="empleadoNacimiento"></div>
                </div>
            
                <div class="row mb-3">
                    <div class="col-5 fw-bold">Estado:</div>
                    <div class="col-7">
                    <span id="empleadoEstado" class="badge bg-success"></span>
                    </div>
                </div>
            </div>
        </div>
      </div>
    </div>
  </div>

@endsection

<script>

    document.addEventListener('DOMContentLoaded', () => {

        const modalEmployee = document.getElementById('modalEmployeeForm');
            
        const modalInstance = new bootstrap.Modal(modalEmployee);

        const form=document.getElementById('formEmployee');

        const modalDetalle=document.getElementById('modalDetalleEmployeer');

        const modalDetalleInstance=new bootstrap.Modal(modalDetalle);

        document.getElementById('btnMostrarModalForm').addEventListener('click', () => {
            showModalRegistrar(modalInstance);
        });

        document.getElementById('btnCerrarModal').addEventListener('click', ()=>{
            cerrarModal(modalInstance, form);
        });

        document.getElementById('btnGuardar').addEventListener('click', ()=>{
            save(form, modalInstance)
        })

        document.getElementById('employeeTableBody').addEventListener('click', (e) => {
        
            if (e.target.closest('.btnEditar')) {
                const btn = e.target.closest('.btnEditar');
                const id = btn.getAttribute('data-id');
                findEmployee(id, modalInstance);
            }
            if (e.target.closest('.btnEliminar')) {
                const btn=e.target.closest('.btnEliminar');
                const id= btn.getAttribute('data-id');
                deleteEmployeer(id);
            }

            if (e.target.closest('.btnVer')) {
                const btn=e.target.closest('.btnVer');
                const id=btn.getAttribute('data-id');
                verDetalle(id, modalDetalleInstance);
            }
        });

        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('btnEliminarHorario')) {
                const contenedor = e.target.closest('.mb-3').querySelector('.horario-item').parentElement;
                const lapsos = contenedor.querySelectorAll('.horario-item');
                if (lapsos.length>1) {
                    
                    e.target.closest('.horario-item').remove();
                }
            }
        });

        const inputFiltro=document.getElementById('busquedaEmpleado');
        inputFiltro.addEventListener('input', async function () {
            
            const cadena=inputFiltro.value;
            try{
                const response=await fetch(`/employee/filter?q=${encodeURIComponent(cadena)}`);
                const responseBody=await response.json();
                generateTable(responseBody.lista);
                updatePaginationLinks(responseBody.pagination);

            }catch(error){
                console.log(error);
            }
        });
        


    });

    function agregarHorario(dia) {
        const contenedor = document.getElementById(`contenedor-${dia}`);
        const div = document.createElement('div');
        div.className = 'row align-items-center mb-2 horario-item';
        div.innerHTML = `
            <div class="col-5">
            <input type="time" class="form-control" name="${dia}_inicio[]">
            </div>
            <div class="col-5">
            <input type="time" class="form-control" name="${dia}_fin[]">
            </div>
            <div class="col-2">
            <button type="button" class="btn btn-danger btn-sm btnEliminarHorario">&times;</button>
            </div>
        `;
        contenedor.appendChild(div);
    }
    

    function showModalRegistrar(modalInstance){
        document.getElementById('labelModalTitle').innerText='Registrar empleado';
        document.getElementById('btnGuardar').innerText='Guardar';
        modalInstance.show();
    }

    function cerrarModal(modalInstance, form){
        
        form.reset();

        const dias = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado'];

        dias.forEach(dia => {
            const contenedor = document.getElementById(`contenedor-${dia}`);
            
            contenedor.innerHTML = '';

            const fila = document.createElement('div');
            fila.classList.add('row', 'align-items-center', 'mb-2', 'horario-item');
            fila.innerHTML = `
                <div class="col-5">
                    <input type="time" class="form-control" name="${dia}_inicio[]">
                </div>
                <div class="col-5">
                    <input type="time" class="form-control" name="${dia}_fin[]">
                </div>
                <div class="col-2">
                    <button type="button" class="btn btn-danger btn-sm btnEliminarHorario">&times;</button>
                </div>
            `;
            contenedor.appendChild(fila);
        });


        modalInstance.hide();
    }

    async function save(form, modalInstance){
        Swal.fire({
            title: 'Cargando...',
            html: 'Por favor espera un momento',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        
       const page = getCurrentPage();

       console.log('pagina=' + page);

        const formData=new FormData(form);

        try{
            const response=await fetch(`/employees/save?page=${page}`, {
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            });

            const responseBody=await response.json();

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

                Swal.fire({
                    icon: 'success',
                    title: responseBody.success,
                    text: 'Los cambios se han aplicado exitosamente',
                    confirmButtonText: 'Aceptar'
                });
                generateTable(responseBody.employees);

                updatePaginationLinks(responseBody.pagination);

                cerrarModal(modalInstance, form)
            }
        }catch(error){
            console.log(error);
        }


    }

    function generateTable(employees){
        const employeesData=employees.data;

        const tbody = document.querySelector('#empleadosTable tbody');
        tbody.innerHTML = '';

        employeesData.forEach((employee, index) => {
            const tr = document.createElement('tr');
            tr.classList.add('animate__animated', 'animate__fadeIn');

            tr.innerHTML = `
                <th scope="row">${index + 1}</th>
                <td>${employee.nombre}</td>
                <td>${employee.correo}</td>
                <td>${employee.cargo}</td>
                <td>
                    <button data-id="${ employee.id }" class="btnEditar btn btn-primary btn-sm me-2 rounded-circle"><i class="fa-solid fa-user-pen"></i></button>
                    <button data-id="${ employee.id }" class="btnEliminar btn btn-danger btn-sm me-2 rounded-circle"><i class="fa-solid fa-trash"></i></button>
                    <a href="/horarios/get/${ employee.id }" class="btn btn-primary btn-sm me-2 rounded-circle">
                        <i class="fa-solid fa-calendar"></i>
                    </a>
                    <button data-id="${ employee.id }" class="btnVer btn btn-primary btn-sm me-2 rounded-circle"><i class="fa-solid fa-eye"></i></button>
                </td>
                `;
                tbody.appendChild(tr);
                    
        });
    }

    //<img src="/storage/${employee.url_image}" alt="Imagen subida" class="img-fluid rounded shadow" style="max-width: 300px;">
    async function findEmployee(id, modalInstance){
        
        try{
            const response=await fetch(`/employee/find/${id}`);
            const responseBody=await response.json();


            const employeeInfo=responseBody.employee;
            const horarios=responseBody.horarios;

            console.log(horarios);

            document.getElementById('idEmployee').value = employeeInfo.id;
            document.getElementById('nombre').value = employeeInfo.nombre;
            document.getElementById('correo').value = employeeInfo.correo;
            document.getElementById('fechaIngreso').value= employeeInfo.fecha_ingreso;
            document.getElementById('fechaNacimiento').value=employeeInfo.fecha_nac;
            document.getElementById('cargo').value=employeeInfo.cargo_id;
            const dias = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado'];
            dias.forEach(dia => {
                const contenedor = document.getElementById(`contenedor-${dia}`);
                contenedor.innerHTML = '';
            });

            const horariosPorDia = {
                lunes: [],
                martes:[],
                miercoles:[],
                jueves:[],
                viernes:[],
                sabado:[]
            };

            dias.forEach(dia=>{
                horariosPorDia[dia]=horarios.filter(h=>h.dia.toLowerCase()===dia);
            })
            
            dias.forEach(dia => {
                const contenedor = document.getElementById(`contenedor-${dia}`);
                const registros = horariosPorDia[dia] || [];

                registros.forEach((h, index) => {
                    const div = document.createElement('div');
                    div.classList.add('row', 'align-items-center', 'mb-2', 'horario-item');
                    div.innerHTML = `
                        <div class="col-5">
                            <input type="time" class="form-control" name="${dia}_inicio[]" value="${h.hora_inicio.slice(0, 5)}">
                        </div>
                        <div class="col-5">
                            <input type="time" class="form-control" name="${dia}_fin[]" value="${h.hora_fin.slice(0, 5)}">
                        </div>
                        <div class="col-2">
                            <button type="button" class="btn btn-danger btn-sm btnEliminarHorario">&times;</button>
                        </div>
                    `;
                    contenedor.appendChild(div);
                });

                if (registros.length === 0) {
                    const div = document.createElement('div');
                    div.classList.add('row', 'align-items-center', 'mb-2', 'horario-item');
                    div.innerHTML = `
                        <div class="col-5">
                            <input type="time" class="form-control" name="${dia}_inicio[]">
                        </div>
                        <div class="col-5">
                            <input type="time" class="form-control" name="${dia}_fin[]">
                        </div>
                        <div class="col-2">
                            <button type="button" class="btn btn-danger btn-sm btnEliminarHorario">&times;</button>
                        </div>
                    `;
                    contenedor.appendChild(div);
                }
            });


            document.getElementById('labelModalTitle').innerText='Actualizar empleado';
            document.getElementById('btnGuardar').innerText='Actualizar';

            modalInstance.show();

        }catch(error){
            console.error(error);
        }
    }

    async function deleteEmployeer(id){
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
            const page = getCurrentPage();
            try{
                const response=await fetch(`/employee/delete/${id}?page=${page}`, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                const responseBody=await response.json();
                console.log(responseBody);
                
                if (response.ok) {
                    
                    Swal.fire({
                        title: "Eliminado!",
                        text: responseBody.message,
                        icon: "success"
                    });
                    generateTable(responseBody.employees);
                    const nuevaPagina=responseBody.employees.current_page;

                    updatePaginationLinks(responseBody.pagination);
                    history.pushState(null, '', `?page=${nuevaPagina}`);
                }

            }catch(error){
                console.log(error);
            }

        }
            
    }

    async function verDetalle(id, modalDetalleInstance){
        try{
            const response=await fetch(`/employee/find/${id}`);

            const responseBody=await response.json();

            console.log(responseBody);

            document.getElementById('empleadoImagen').src=`/storage/${responseBody.employee.url_image}`;
            document.getElementById('empleadoNombre').textContent=responseBody.employee.nombre;
            document.getElementById('empleadoCorreo').textContent=responseBody.employee.correo;
            document.getElementById('empleadoIngreso').textContent=responseBody.employee.fecha_ingreso;
            document.getElementById('empleadoNacimiento').textContent=responseBody.employee.fecha_nac;
            document.getElementById('empleadoEstado').textContent=(responseBody.employee.activo == 1)? 'Activo':'Inactivo';

            modalDetalleInstance.show();

        }catch(error){
            console.log(error);
        }
    }

    function getCurrentPage(){
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get('page') || 1;
        
    }


    function updatePaginationLinks(pagination){
        document.querySelector('#paginator').innerHTML = pagination;
    }
</script>

