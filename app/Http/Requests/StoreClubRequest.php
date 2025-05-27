<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClubRequest extends FormRequest
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
            'name_club' => 'required|string|max:255|unique:clubs,name_club',
            'city' => 'required|string|max:255',
            'foundation' => 'required|date',
            'api_id' => 'sometimes|integer|unique:clubs,api_id',
            'image' => 'sometimes|url'
        ];
    }
}
