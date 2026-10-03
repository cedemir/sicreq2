<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ArtistaRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'artista_nome' => ['required', 'string', 'max:50'],
            'artista_historico' => ['nullable', 'string', 'max:255'],
            'artista_foto' => ['nullable', 'image', 'max:2048'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'artista_nome' => 'nome',
            'artista_historico' => 'histórico',
            'artista_foto' => 'foto',
        ];
    }
}
