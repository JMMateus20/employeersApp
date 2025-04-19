<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ExcepcionRequest extends FormRequest
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
            'fechaExcepcion'=>'required|date',
            'horaInicio'=>'required|date_format:H:i',
            'horaFin'=>'required|date_format:H:i|after:horaInicio',
            'motivo'=>'required|string|max:255'
        ];
    }


    public function messages()
    {
        return [
            'fechaExcepcion.required' => 'La fecha de la excepción es un campo obligatorio',
            'fechaExcepcion.date'=>'La fecha de la excepción debe ser una fecha válida',
            'horaInicio.required'=>'La hora de inicio es requerida',
            'horaFin.required'=>'La hora final es requerida',
            'horaFin.after'=>'La hora final debe ser mayor a la hora de inicio',
            'motivo.required'=>'El motivo es un campo requerido'
            
            
        ];
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
