<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmployeeRequest;
use App\Models\Cargo;
use App\Models\Employee;
use App\Models\Horario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PhpParser\Node\Stmt\Foreach_;

class EmployeeController extends Controller
{
    public function getAll(){
        $employeerList=DB::table('employees')
            ->join('cargos', 'employees.cargo_id', '=', 'cargos.id')
            ->select('employees.*', 'cargos.cargo')
            ->get();
        return view('employees', [
            'employees'=>$employeerList,
            'cargosSelect'=>Cargo::all()
        ]);
    }

    public function save(EmployeeRequest $req){

        $rutaImagen = null;

        $employeeNew=null;

        $defaultURLImage=config('custom.default_image_url');

        if ($req->hasFile('imagen') ) {
            $rutaImagen = $req->file('imagen')->store('imagenes', 'public');
        }

        if ($req->idEmployee!=null && $req->idEmployee>0) {
            $employeeNew=Employee::find($req->idEmployee);
            if ($rutaImagen==null) {
                if ($employeeNew->url_image!='') {
                    $rutaImagen=$employeeNew->url_image;
                }else{
                    $rutaImagen=$defaultURLImage;
                }
            }else{
                if ($employeeNew->url_image!='' && $employeeNew->url_image!=$defaultURLImage) {
                    Storage::disk('public')->delete($employeeNew->url_image);
                }
            }
        }else{
            $employeeNew=new Employee();
            if ($rutaImagen==null) {
                $rutaImagen=$defaultURLImage;
            }
        }

        try{
            DB::beginTransaction();
            $employeeNew->nombre=$req->nombre;
            $employeeNew->correo=$req->correo;
            $employeeNew->fecha_ingreso=$req->fechaIngreso;
            $employeeNew->fecha_nac=$req->fechaNacimiento;
            $employeeNew->activo=true;
            $employeeNew->url_image=$rutaImagen;
            $employeeNew->cargo_id=$req->cargo;

            $employeeNew->save();


            self::guardarHorarios($req, $employeeNew);


            DB::commit();

            return response()->json([
                'success'=>'Cambios realizados',
                'employees'=>self::getAllAux()
            ], 200);

        }catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function getAllAux(){
        return DB::table('employees')
            ->join('cargos', 'employees.cargo_id', '=', 'cargos.id')
            ->select('employees.*', 'cargos.cargo')
            ->get();
    }


    private function guardarHorarios(EmployeeRequest $req, Employee $employee){
        if ($req->idEmployee!=null && $req->idEmployee>0) {

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
            ->select('dias.dia', 'horarios.hora_inicio', 'horarios.hora_fin')
            ->get();
        return response()->json([
            'employee'=>$employeeBD,
            'horarios'=>$employeerList
        ]);
    }

    public function delete($id){
        try{
            DB::beginTransaction();
            $employeeBD=Employee::find($id);
            if ($employeeBD->url_image != config('custom.default_image_url')) {
                Storage::disk('public')->delete($employeeBD->url_image);
            }
            $employeeBD->delete();
            DB::commit();
            return response()->json([
                'message'=>'Empleado eliminado con éxito',
                'employees'=>self::getAllAux()
            ], 200);

        }catch(\Exception $e){
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
