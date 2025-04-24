<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class EventoRequest extends FormRequest
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
            'titulo'=>'required|string|max:255',
            'descripcion'=>'required|string|max:255',
            'fecha'=>'required|date',
            'hora_inicio'=>'required|date_format:H:i',
            'hora_fin'=>'required|date_format:H:i|after:hora_inicio',
            'asistentes' => 'required|array|min:1',
            'asistentes.*' => 'required|distinct|integer|exists:employees,id'
        ];
    }

    public function messages()
    {
        return [
            'titulo.required' => 'El título es un campo obligatorio',
            'descripcion.required'=>'La descripción es un campo obligatorio',
            'fecha.required'=>'La fecha es un campo obligatorio',
            'fecha.date'=>'La fecha debe tener un formato de fecha válido',
            'hora_inicio.required'=>'La hora de inicio es requerida',
            'hora_fin.required'=>'La hora final es requerida',
            'hora_fin.after'=>'La hora final debe ser mayor a la hora de inicio',
            'asistentes.required'=>'Debe haber al menos un asistente en el evento'
            
            
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
