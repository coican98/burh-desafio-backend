<?php

namespace App\Http\Requests;

use App\Models\Usuario;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class CandidatarVagaRequest extends FormRequest
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
            'usuario_id' => 'required|exists:usuarios,id',
            'vaga_id' => 'required|exists:vagas,id',
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
            if ($this->usuario_id && $this->vaga_id) {
                $usuario = Usuario::with('vagas')->find($this->usuario_id);
                if ($usuario && $usuario->vagas->contains($this->vaga_id)) {
                    $validator->errors()->add('vaga_id', 'O usuário já se candidatou a esta vaga!');
                }
            }
        });
    }

    public function messages()
    {
        return [
            'usuario_id.exists' => 'Usuário não encontrado.',
            'vaga_id.exists' => 'Vaga não encontrada.',
        ];
    }
}
