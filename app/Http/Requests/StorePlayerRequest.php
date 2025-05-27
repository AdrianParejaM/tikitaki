<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePlayerRequest extends FormRequest
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
            'name_player' => 'required|string|max:255',
            'position' => 'required|in:Goalkeeper,Defender,Midfielder,Forward',
            'market_value' => 'required|integer|min:0',
            'club_id' => 'required|exists:clubs,id',
            'api_id' => 'sometimes|integer|unique:players,api_id',
            'nationality' => 'sometimes|string|max:3'
        ];
    }
}
