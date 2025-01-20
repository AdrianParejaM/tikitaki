<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            'nickname'=>'required|max:30|unique:users',
            'name'=>'required|max:255|$this->string()',
            'email'=>'required|email|unique:users',
            'password'=>'required|password|string|min:6'
        ];
    }

    public function messages()
    {

        return [
            'nickname.required'=>'El nombre de usuario es obligatorio',
            'nickname.max'=>'El nombre de usuario es demasiado largo',
            'nickname'=>'Error en el nombre de usuario',
            'name.required'=>'El nombre es obligatorio',
            'name.max'=>'El nombre es demasiado largo',
            'name'=>'Error en el nombre',
            'email'=>'Error en el mail',
            'password'=>'Error en la contraseña'
        ];

    }

}
