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
