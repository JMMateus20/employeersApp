<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class EmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array                       
   
    {
        return [
            'nombre'=>'required|string|max:255',
            'correo' => [
                'required',
                'string',
                'max:255',
                Rule::unique('employees', 'correo')->ignore($this->idEmployee),
            ],
            'fechaIngreso'=>'required|date',
            'fechaNacimiento'=>'required|date',
            'imagen'=>'nullable|image|max:2048',

            'lunes_inicio.*'=>'nullable|date_format:H:i',
            'lunes_fin.*'=>'nullable|date_format:H:i',
            'martes_inicio.*'=>'nullable|date_format:H:i',
            'martes_fin.*'=>'nullable|date_format:H:i',
            'miercoles_inicio.*'=>'nullable|date_format:H:i',
            'miercoles_fin.*'=>'nullable|date_format:H:i',
            'jueves_inicio.*'=>'nullable|date_format:H:i',
            'jueves_fin.*'=>'nullable|date_format:H:i',
            'viernes_inicio.*'=>'nullable|date_format:H:i',
            'viernes_fin.*'=>'nullable|date_format:H:i',
            'sabado_inicio.*'=>'nullable|date_format:H:i',
            'sabado_fin.*'=>'nullable|date_format:H:i'
        ];
    }

    public function messages()
    {
        return [
            'nombre.required' => 'El nombre del empleado es un campo obligatorio',
            'correo.unique'=>'El correo ingresado ya se encuentra siendo utilizado',
            'correo.required'=>'El correo del empleado es requerido',
            'fechaIngreso.required'=>'La fecha de ingreso es requerida',
            'fechaIngreso.date'=>'La fecha de ingreso debe ser una fecha válida',
            'fechaNacimiento.required'=>'La fecha de nacimiento es requerida',
            'fechaNacimiento.date'=>'La fecha de nacimiento debe ser una fecha válida',
            'imagen.image'=>'El archivo de imagen debe estar en formato PNG O JPEG',
            '*.date_format' => 'El formato de la hora debe ser H:i (por ejemplo, 08:30).'
            
            
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $dias = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado'];

            foreach ($dias as $dia) {
                $inicios = $this->input("{$dia}_inicio", []);
                $fines = $this->input("{$dia}_fin", []);

                for ($i = 0; $i < count($inicios); $i++) {
                    $inicio = $inicios[$i] ?? null;
                    $fin = $fines[$i] ?? null;

                    if ($inicio && $fin && $inicio >= $fin) {
                        $validator->errors()->add("{$dia}_fin.$i", "La hora de fin debe ser mayor que la de inicio en $dia (horario ".($i+1).").");
                    }

                    if (
                        ($inicio && !$fin) ||
                        (!$inicio && $fin) ||
                        ($inicio===null | $fin===null) ||
                        ($inicio === '' && $fin === '')
                    ) {
                        $validator->errors()->add("{$dia}_inicio.$i", "Ambas horas (inicio y fin) son requeridas en $dia (horario ".($i+1).").");
                    }
                }
            }
        });
    }


    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Errores de validación',
            'errors' => $validator->errors()
        ], 400));
    }
}
