<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class HistoriaRequest extends FormRequest
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
            'historia_numero' => [
                'required',
                'integer',
                'min:1',
                Rule::unique('historia', 'historia_numero')
                    ->where('titulo_codigo', $this->integer('titulo_codigo'))
                    ->where('exemplar_codigo', $this->integer('exemplar_codigo'))
                    ->ignore($this->route('historia')),
            ],
            'historia_titulo' => ['required', 'string', 'max:50'],
            'historia_resumo' => ['nullable', 'string', 'max:16000'],
            'historia_cor' => ['boolean'],
            'historia_original' => ['nullable', 'string', 'max:50'],
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
            'historia_numero' => 'história (número)',
            'historia_titulo' => 'título da história',
            'historia_resumo' => 'resumo',
            'historia_cor' => 'colorida',
            'historia_original' => 'título original',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'historia_cor' => $this->boolean('historia_cor'),
        ]);
    }
}
