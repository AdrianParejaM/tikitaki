<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLeagueRequest extends FormRequest
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
            'name_league' => 'sometimes|string|max:255|unique:leagues,name_league,',
            'description' => 'sometimes|string|nullable',
            'user_id' => 'sometimes|exists:users,id',
            'creation_date' => 'sometimes|date',
        ];
    }
}
