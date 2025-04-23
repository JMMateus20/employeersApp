<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmployeeRequest;
use App\Models\Cargo;
use App\Models\Employee;
use App\Models\Horario;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PhpParser\Node\Stmt\Foreach_;

use function PHPUnit\Framework\isEmpty;

class EmployeeController extends Controller
{
    public function getAll(){
        $employeerList=DB::table('employees')
            ->join('cargos', 'employees.cargo_id', '=', 'cargos.id')
            ->where('employees.activo', '=', 1)
            ->select('employees.*', 'cargos.cargo')
            ->paginate(1);
        return view('employees', [
            'employees'=>$employeerList,
            'cargosSelect'=>Cargo::all()
        ]);
    }

    public function save(EmployeeRequest $req){

        $page = $req->query('page', 1);

        $rutaImagen = null;

        $employeeNew=null;

        $defaultURLImage=config('custom.default_image_url');

        if ($req->hasFile('imagen') ) {
            $rutaImagen = $req->file('imagen')->store('imagenes', 'public');
        }

        $employeeNew = $req->idEmployee ? Employee::find($req->idEmployee) : new Employee();
        
        if ($rutaImagen) {
            if ($employeeNew->url_image) {
                Storage::disk('public')->delete($employeeNew->url_image);
            }
            $employeeNew->url_image = $rutaImagen;
        }else{
            if (!$employeeNew->url_image) {
                $employeeNew->url_image = $defaultURLImage;
            }
        }
       
   

        try{
            DB::beginTransaction();
            $employeeNew->nombre=$req->nombre;
            $employeeNew->correo=$req->correo;
            $employeeNew->fecha_ingreso=$req->fechaIngreso;
            $employeeNew->fecha_nac=$req->fechaNacimiento;
            $employeeNew->activo=true;
            $employeeNew->cargo_id=$req->cargo;

            $employeeNew->save();


            $this->guardarHorarios($req, $employeeNew);


            DB::commit();

            $listadoActualizado=$this->getAllAux($page);
            

            return response()->json([
                'success'=>'Cambios realizados',
                'employees'=>$listadoActualizado,
                'pagination'=>$this->renderPaginationView($listadoActualizado)
            ], 200);

        }catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function getAllAux($page){
        Paginator::currentPageResolver(function () use ($page) {
            return $page;
        });
        return DB::table('employees')
            ->join('cargos', 'employees.cargo_id', '=', 'cargos.id')
            ->where('employees.activo', '=', 1)
            ->select('employees.*', 'cargos.cargo')
            ->paginate(1);
    }


    private function guardarHorarios(EmployeeRequest $req, Employee $employee){
        if ($req->idEmployee) {

            $employee->horarios()->delete();

        }

        $dias= [
            'lunes' => 1,
            'martes' => 2,
            'miercoles' => 3,
            'jueves' => 4,
            'viernes' => 5,
            'sabado' => 6
        ];
        foreach ($dias as $nomDia=>$diaId) {
            $inicios = $req->input("{$nomDia}_inicio", []);
            $fines = $req->input("{$nomDia}_fin", []);
            foreach ($inicios as $index => $inicio) {
                
                Horario::create([
                    'dia_id'=>$diaId,
                    'hora_inicio'=>$inicio,
                    'hora_fin'=>$fines[$index],
                    'employee_id'=>$employee->id
                ]);
            }
        }
            
            
        


    }

    public function find($id){

        $employeeBD=Employee::find($id);
        $employeerList=DB::table('employees')
            ->join('horarios', 'employees.id', '=', 'employee_id')
            ->join('dias', 'horarios.dia_id', '=', 'dias.id')
            ->where('horarios.employee_id', '=', $id)
            ->select('dias.dia', 'horarios.hora_inicio', 'horarios.hora_fin')
            ->get();
        return response()->json([
            'employee'=>$employeeBD,
            'horarios'=>$employeerList
        ]);
    }

    public function delete($id, Request $req){
        $page = $req->query('page', 1);
        try{
            DB::beginTransaction();
            
            $employeeBD=Employee::find($id);

            $employeeBD->activo=0;
            /*
            if ($employeeBD->url_image != config('custom.default_image_url')) {
                Storage::disk('public')->delete($employeeBD->url_image);
            }
            $employeeBD->delete();*/
            $employeeBD->save();
            DB::commit();

            $listadoActualizado=self::getAllAux($page);

            if ($listadoActualizado->isEmpty() && $page>1) {
                
                $listadoActualizado=$this->getAllAux($page-1);
            }
             

            return response()->json([
                'message'=>'Empleado desactivado con éxito',
                'employees'=>$listadoActualizado,
                'pagination'=>$this->renderPaginationView($listadoActualizado)
            ], 200);

        }catch(\Exception $e){
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function filtrar(Request $req){
        $page=!$req->input('page') ? 1 : $req->query('page');
        Paginator::currentPageResolver(function () use ($page) {
            return $page;
        });

        $termino = $req->input('q');
        
        $employeerList=DB::table('employees')
        ->join('cargos', 'employees.cargo_id', '=', 'cargos.id')
        ->where('employees.activo', '=', 1)
        ->where('employees.nombre', 'LIKE', "%{$termino}%")
        ->select('employees.*', 'cargos.cargo')
        ->paginate(1);
        if ($employeerList->total()===0) {
            $employeerList=$this->getAllAux(1);
            
        }
        
        
        return ($page===1) ?
        response()->json([
            'lista'=>$employeerList,
            'pagination'=>$this->renderPaginationView($employeerList, '/employee/filter?q='.$termino)
        ])
        :
        view('employees', [
            'employees'=>$employeerList,
            'cargosSelect'=>Cargo::all(),
            'pagination'=>$this->renderPaginationView($employeerList, '/employee/filter?q='.$termino),
            'termino'=>$termino
        ]);
    }


    private function renderPaginationView($listado, $defaultPath='/'){
        $listado->withPath($defaultPath); //colocar el path original de la ruta de la tabla o listado de registros
        return view('partials.pagination', ['employees'=>$listado])->render();
        
    }
}
