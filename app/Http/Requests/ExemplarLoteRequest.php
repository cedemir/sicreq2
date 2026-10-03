<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class ExemplarLoteRequest extends FormRequest
{
    /**
     * Maximum number of copies generated in a single run.
     */
    public const MAX_EXEMPLARES = 1000;

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
            'exemplar_inicial' => ['required', 'integer', 'min:1'],
            'exemplar_final' => ['required', 'integer', 'min:1', 'gte:exemplar_inicial'],
            'armazenagem_codigo' => ['nullable', 'integer', Rule::exists('armazenagem', 'armazenagem_codigo')],
            'tamanho_codigo' => ['nullable', 'integer', Rule::exists('tamanho', 'tamanho_codigo')],
            'exemplar_numeropagina' => ['nullable', 'integer', 'min:0'],
        ];
    }

    /**
     * Get the "after" validation callables for the request.
     *
     * @return array<int, callable>
     */
    public function after(): array
    {
        return [
            function (Validator $validator): void {
                if ($validator->errors()->hasAny(['exemplar_inicial', 'exemplar_final'])) {
                    return;
                }

                if ($this->quantidade() > self::MAX_EXEMPLARES) {
                    $validator->errors()->add(
                        'exemplar_final',
                        'É possível gerar no máximo '.self::MAX_EXEMPLARES.' exemplares por vez.',
                    );
                }
            },
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
            'exemplar_inicial' => 'exemplar inicial',
            'exemplar_final' => 'exemplar final',
            'armazenagem_codigo' => 'armazenagem',
            'tamanho_codigo' => 'tamanho',
            'exemplar_numeropagina' => 'nº de páginas',
        ];
    }

    /**
     * Get the copy numbers requested, from the first to the last one.
     *
     * @return array<int, int>
     */
    public function numeros(): array
    {
        return range($this->integer('exemplar_inicial'), $this->integer('exemplar_final'));
    }

    /**
     * Get how many copies were requested.
     */
    public function quantidade(): int
    {
        return $this->integer('exemplar_final') - $this->integer('exemplar_inicial') + 1;
    }
}
