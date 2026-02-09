<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    public function index(Request $request)
    {
        $query = Usuario::with('vagas');

        if ($request->has('filtro')) {
            $filtro = $request->filtro;
            $query->where(function ($q) use ($filtro) {
                $q->where('nome', 'like', "%{$filtro}%")
                  ->orWhere('email', 'like', "%{$filtro}%")
                  ->orWhere('cpf', 'like', "%{$filtro}%");
            });
        }

        return response()->json($query->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|email|unique:usuarios,email',
            'cpf' => 'required|string|unique:usuarios,cpf',
            'idade' => 'required|integer',
        ], [
            'required' => 'O campo :attribute é obrigatório.',
            'string' => 'O campo :attribute deve ser uma string.',
            'max' => 'O campo :attribute não pode ter mais que :max caracteres.',
            'unique' => 'O :attribute informado já está em uso.',
            'email' => 'O campo :attribute deve ser um endereço de e-mail válido.',
            'integer' => 'O campo :attribute deve ser um número inteiro.',
        ], [
            'nome' => 'nome',
            'email' => 'e-mail',
            'cpf' => 'CPF',
            'idade' => 'idade',
        ]);
        $cpf = preg_replace('/[^0-9]/', '', $request->cpf);
        $validarCPF = $this->validarCPF($cpf);
        // dd($validarCPF);
        if ($validarCPF->getStatusCode() != 200) {
            return $validarCPF;
        }
        $usuario = Usuario::create([
            'nome' => $request->nome,
            'email' => $request->email,
            'cpf' => $cpf,
            'idade' => $request->idade,
        ]);

        return response()->json([
            'message' => 'Usuário criado com sucesso.',
            'usuario' => $usuario
        ], 201);
    }

    public function show($id)
    {
        $usuario = Usuario::with('vagas')->find($id);

        if (!$usuario) {
            return response()->json(['message' => 'Usuário não encontrado'], 404);
        }

        return response()->json($usuario);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'email' => 'email|unique:usuarios,email,' . $id,
            'cpf' => 'string|unique:usuarios,cpf,' . $id,
        ], [
            'email' => 'O campo :attribute deve ser um endereço de e-mail válido.',
            'unique' => 'O :attribute informado já está em uso.',
            'string' => 'O campo :attribute deve ser uma string.',
        ], [
            'email' => 'e-mail',
            'cpf' => 'CPF',
        ]);
        $usuario = Usuario::find($id);

        if (!$usuario) {
            return response()->json(['message' => 'Usuário não encontrado'], 404);
        }

        $cpf = preg_replace('/[^0-9]/', '', $request->cpf);
        $validarCPF = $this->validarCPF($cpf);
        if ($validarCPF->getStatusCode() != 200) {
            return $validarCPF;
        }

        $update = [
            "nome" => $usuario->nome,
            "email" => $usuario->email,
            "cpf" => $usuario->cpf,
            "idade" => $usuario->idade,
        ];

        if($request->email){
            $update['email'] = $request->email;
        }
        if($request->cpf){
            $update['cpf'] = $cpf;
        }
        if($request->idade){
            $update['idade'] = $request->idade;
        }
        if($request->nome){
            $update['nome'] = $request->nome;
        }

        $usuario->update($update);

        return response()->json([
            'message' => 'Usuário atualizado com sucesso.',
            'usuario' => $usuario
        ]);
    }

    public function destroy($id)
    {
        $usuario = Usuario::find($id);

        if (!$usuario) {
            return response()->json(['message' => 'Usuário não encontrado'], 404);
        }

        $usuario->delete();

        return response()->json(['message' => 'Usuário removido com sucesso.']);
    }
    public function validarCPF($cpf)
    {
        $cpf = preg_replace('/[^0-9]/', '', $cpf);

        if (strlen($cpf) != 11) {
            return response()->json(['message' => 'O CPF informado não possui 11 caracteres.'], 400);
        }

        if (preg_match('/^(.)\1{10}$/', $cpf)) {
            return response()->json(['message' => 'O CPF informado é inválido (caracteres repetidos).'], 400);
        }
        // 1. Validação do primeiro dígito
        $soma1 = 0;
        for ($i = 0; $i < 9; $i++) {
            $soma1 += $cpf[$i] * (10 - $i);
        }
        $resto1 = ($soma1 * 10) % 11;
        if ($resto1 == 10) $resto1 = 0;

        if ($resto1 != $cpf[9]) {
            return response()->json(['message' => 'O CPF informado não é válido (1º dígito verificador).'], 400);
        }

        // 2. Validação do segundo dígito
        $soma2 = 0;
        for ($i = 0; $i < 10; $i++) {
            $soma2 += $cpf[$i] * (11 - $i);
        }
        $resto2 = ($soma2 * 10) % 11;
        if ($resto2 == 10) $resto2 = 0;

        if ($resto2 != $cpf[10]) {
            return response()->json(['message' => 'O CPF informado não é válido (2º dígito verificador).'], 400);
        }

        return response()->json(['message' => 'O CPF informado é válido.'], 200);
    }
}
