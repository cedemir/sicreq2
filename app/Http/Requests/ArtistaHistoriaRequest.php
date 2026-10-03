<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ArtistaHistoriaRequest extends FormRequest
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
            'titulo_codigo' => ['required', 'integer', Rule::exists('titulo', 'titulo_codigo')],
            'exemplar_codigo' => [
                'required',
                'integer',
                'min:1',
                Rule::exists('exemplar', 'exemplar_codigo')
                    ->where('titulo_codigo', $this->integer('titulo_codigo')),
            ],
            'historia_codigo' => [
                'required',
                'integer',
                'min:1',
                Rule::exists('historia', 'historia_numero')
                    ->where('titulo_codigo', $this->integer('titulo_codigo'))
                    ->where('exemplar_codigo', $this->integer('exemplar_codigo')),
            ],
            'artista_codigo' => [
                'required',
                'integer',
                Rule::exists('artista', 'artista_codigo'),
                Rule::unique('artista_historia', 'artista_codigo')
                    ->where('titulo_codigo', $this->integer('titulo_codigo'))
                    ->where('exemplar_codigo', $this->integer('exemplar_codigo'))
                    ->where('historia_codigo', $this->integer('historia_codigo'))
                    ->ignore($this->route('artista_historia')),
            ],
            'participa_artistahistoria' => ['nullable', 'integer', 'min:0', 'max:32767'],
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
            'titulo_codigo' => 'título',
            'exemplar_codigo' => 'exemplar (nº)',
            'historia_codigo' => 'história (número)',
            'artista_codigo' => 'artista',
            'participa_artistahistoria' => 'participação',
        ];
    }
}
