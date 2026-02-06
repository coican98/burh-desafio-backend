<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Http\Request;

class EmpresaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Empresa::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'required|string',
            'cnpj' => 'required|string|unique:empresas,cnpj',
            'plano' => 'required|in:Free,Premium',
        ]);

        $empresa = Empresa::create($request->all());

        return response()->json([
            'message' => 'Empresa cadastrada com sucesso.',
            'empresa' => $empresa
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $empresa = Empresa::with('vagas')->find($id);

        if (!$empresa) {
            return response()->json(['message' => 'Empresa não encontrada'], 404);
        }

        return response()->json($empresa);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $empresa = Empresa::find($id);

        if (!$empresa) {
            return response()->json(['message' => 'Empresa não encontrada'], 404);
        }

        $request->validate([
            'cnpj' => 'string|unique:empresas,cnpj,' . $id,
            'plano' => 'in:Free,Premium',
        ]);

        $empresa->update($request->all());

        return response()->json([
            'message' => 'Empresa atualizada com sucesso.',
            'empresa' => $empresa
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $empresa = Empresa::find($id);

        if (!$empresa) {
            return response()->json(['message' => 'Empresa não encontrada'], 404);
        }

        $empresa->delete();

        return response()->json(['message' => 'Empresa removida com sucesso.']);
    }
}
