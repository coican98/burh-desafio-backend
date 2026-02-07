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

        $usuario = Usuario::create($request->all());

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
        $usuario = Usuario::find($id);

        if (!$usuario) {
            return response()->json(['message' => 'Usuário não encontrado'], 404);
        }

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

        $usuario->update($request->all());

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
}
