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
            'name_league' => 'required|string|max:255|unique:leagues,name_league',
            'description' => 'nullable|string',
            'user_id' => 'required|exists:users,id',
            'creation_date' => 'required|date',
        ];
    }

    public function messages()
    {

        return [
            'name_league.max'=>'El nombre es demasiado largo',
            'name_league'=>'Error en el nombre',
            'description'=>'Error en la descripción',
            'creation_date'=>'Error en la fecha de creación'
        ];

    }
}
