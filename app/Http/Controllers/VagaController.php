<?php

namespace App\Http\Controllers;

use App\Http\Requests\CandidatarVagaRequest;
use App\Http\Requests\StoreVagaRequest;
use App\Models\Vaga;
use App\Models\Usuario;
use Illuminate\Http\Request;

class VagaController extends Controller
{
    public function index()
    {
        return response()->json(Vaga::with('empresa')->get());
    }

    public function store(StoreVagaRequest $request)
    {
        // A validação já foi feita automaticamente pelo StoreVagaRequest
        
        $vaga = Vaga::create([
            'empresa_id' => $request->empresa_id,
            'titulo' => $request->titulo,
            'descricao' => $request->descricao,
            'tipo' => $request->tipo,
            'salario' => $request->salario,
            'horario' => $request->horario,
            'status' => 'aberta', // Default value
        ]);

        return response()->json([
            'message' => 'Vaga aberta com sucesso.',
            'vaga' => $vaga
        ], 201);
    }

    public function show($id)
    {
        $vaga = Vaga::with('empresa')->find($id);
        if (!$vaga) {
            return response()->json(['message' => 'Vaga não encontrada'], 404);
        }
        return response()->json($vaga);
    }

    public function update(Request $request, $id)
    {
        // Implementar validação de update se necessário
        $vaga = Vaga::find($id);
        if (!$vaga) {
            return response()->json(['message' => 'Vaga não encontrada'], 404);
        }
        $vaga->update($request->all());
        return response()->json(['message' => 'Vaga atualizada com sucesso', 'vaga' => $vaga]);
    }

    public function destroy($id)
    {
        $vaga = Vaga::find($id);
        if (!$vaga) {
            return response()->json(['message' => 'Vaga não encontrada'], 404);
        }
        $vaga->delete();
        return response()->json(['message' => 'Vaga removida com sucesso']);
    }

    public function candidatar(CandidatarVagaRequest $request)
    {
        // Validação de duplicidade e existência já feita no Request
        
        $usuario = Usuario::find($request->usuario_id);
        $usuario->vagas()->attach($request->vaga_id);

        return response()->json([
            'message' => 'Candidatura realizada com sucesso.',
        ], 201);
    }
}
