<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLineUpRequest extends FormRequest
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
            'name_lineUp' => 'sometimes|string|max:255',
            'description' => 'sometimes|string|nullable',
            'user_id' => 'sometimes|exists:users,id',
            'league_id' => 'sometimes|exists:leagues,id',
        ];
    }
}
