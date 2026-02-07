<?php

namespace App\Http\Requests;

use App\Models\Empresa;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Illuminate\Contracts\Validation\Validator as ValidatorContract;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreVagaRequest extends FormRequest
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
            'empresa_id' => 'required|exists:empresas,id',
            'titulo' => 'required|string|max:255',
            'descricao' => 'required|string',
            'tipo' => 'required|in:CLT,PJ,ESTAGIO,Estágio',
            'salario' => [
                'required_if:tipo,CLT,Estágio,ESTAGIO',
                'nullable',
                'numeric',
                function ($attribute, $value, $fail) {
                    if ($this->tipo === 'CLT' && $value < 1212) {
                        $fail('O salário para vagas CLT deve ser de no mínimo R$ 1.212,00.');
                    }
                },
            ],
            'horario' => [
                'required_if:tipo,CLT,Estágio,ESTAGIO',
                'nullable',
                'integer', // horas por dia
                function ($attribute, $value, $fail) {
                    if (in_array($this->tipo, ['ESTAGIO', 'Estágio']) && $value > 6) {
                        $fail('A carga horária para estágio não pode exceder 6 horas diárias.');
                    }
                },
            ],
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            if ($this->empresa_id) {
                $empresa = Empresa::find($this->empresa_id);
                if ($empresa) {
                    $vagasCount = $empresa->vagas()->count();
                    
                    if ($empresa->plano === 'Free' && $vagasCount >= 5) {
                        $validator->errors()->add('empresa_id', 'Empresas com plano gratuito podem abrir no máximo 5 vagas.');
                    }
                    
                    if ($empresa->plano === 'Premium' && $vagasCount >= 10) {
                        $validator->errors()->add('empresa_id', 'Empresas com plano Premium podem abrir no máximo 10 vagas.');
                    }
                }
            }
        });
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'empresa_id.required' => 'O campo empresa é obrigatório.',
            'empresa_id.exists' => 'A empresa informada não existe.',
            'titulo.required' => 'O campo título é obrigatório.',
            'descricao.required' => 'O campo descrição é obrigatório.',
            'tipo.required' => 'O campo tipo é obrigatório.',
            'tipo.in' => 'O tipo de vaga deve ser CLT, PJ ou Estágio.',
            'salario.required_if' => 'O campo salário é obrigatório quando o tipo é :value.',
            'salario.numeric' => 'O campo salário deve ser um número.',
            'horario.required_if' => 'O campo horário é obrigatório quando o tipo é :value.',
            'horario.integer' => 'O campo horário deve ser um número inteiro.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'empresa_id' => 'empresa',
            'titulo' => 'título',
            'descricao' => 'descrição',
            'salario' => 'salário',
            'horario' => 'horário',
        ];
    }

    protected function failedValidation(ValidatorContract $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => 'Erro de validação.',
            'errors' => $validator->errors(),
        ], 422));
    }
}
