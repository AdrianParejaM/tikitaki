<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLeagueRequest extends FormRequest
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
            'name'=>'required|max:30',
            'description'=>'required|max:255|string',
            'creation_date'=>'required'
        ];
    }

    public function messages()
    {

        return [
            'name.required'=>'El nombre es obligatorio',
            'name.max'=>'El nombre es demasiado largo',
            'name'=>'Error en el nombre',
            'description'=>'Error en la descripción',
            'creation_date'=>'Error en la fecha de creación'
        ];

    }
}
