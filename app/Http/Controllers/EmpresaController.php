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

        $cnpj = preg_replace(['/\D/', '/\./', '/\-/'], '', $request->cnpj);
        $validarCNPJ = $this->validarCNPJ($cnpj);

        if ($validarCNPJ->getStatusCode() != 200) {
            return response()->json(['message' => $validarCNPJ->json('message')], $validarCNPJ->getStatusCode());
        }

        $empresa = Empresa::create([
            'nome' => $request->nome,
            'descricao' => $request->descricao,
            'cnpj' => $cnpj,
            'plano' => $request->plano,
        ]);

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

        $cnpj = preg_replace(['/\D/', '/\./', '/\-/'], '', $request->cnpj);
        $validarCNPJ = $this->validarCNPJ($cnpj);

        if ($validarCNPJ->getStatusCode() != 200) {
            return response()->json(['message' => $validarCNPJ->json('message')], $validarCNPJ->getStatusCode());
        }

        $empresa->update([
            'nome' => $request->nome,
            'descricao' => $request->descricao,
            'cnpj' => $cnpj,
            'plano' => $request->plano,
        ]);

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

    function validarCNPJ($cnpj)
    {
        $cnpj = preg_replace(['/\D/', '/\./', '/\-/'], '', $cnpj);

        if (strlen($cnpj) != 14) {
            return response()->json(['message' => 'O CNPJ informado não possui 14 dígitos.'], 400);
        }

        for ($i = 0; $i < 2; $i++) {
            $soma = 0;
            $multiplicador = 5 - $i;
            for ($j = 0; $j < 12 + $i; $j++) {
                $soma += $cnpj[$j] * $multiplicador;
                $multiplicador--;
            }
            $resto = $soma % 11;
            if ($resto < 2) {
                $resto = 0;
            } else {
                $resto = 11 - $resto;
            }
            if ($resto != $cnpj[12 + $i]) {
                return response()->json(['message' => 'O CNPJ informado não é válido.'], 400);
            }
        }
        return response()->json(['message' => 'O CNPJ informado é válido.'], 200);
    }
}
