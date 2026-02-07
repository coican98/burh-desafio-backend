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
        ], [
            'required' => 'O campo :attribute é obrigatório.',
            'string' => 'O campo :attribute deve ser uma string.',
            'max' => 'O campo :attribute não pode ter mais que :max caracteres.',
            'unique' => 'O :attribute informado já está em uso.',
            'in' => 'O :attribute selecionado é inválido.',
        ], [
            'nome' => 'nome',
            'descricao' => 'descrição',
            'cnpj' => 'CNPJ',
            'plano' => 'plano',
        ]);

        $cnpj = preg_replace(['/\D/', '/\./', '/\-/'], '', $request->cnpj);
        $validarCNPJ = $this->validarCNPJ($cnpj);

        if ($validarCNPJ->getStatusCode() != 200) {
            return $validarCNPJ;
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
        ], [
            'string' => 'O campo :attribute deve ser uma string.',
            'unique' => 'O :attribute informado já está em uso.',
            'in' => 'O :attribute selecionado é inválido.',
        ], [
            'cnpj' => 'CNPJ',
            'plano' => 'plano',
        ]);

        $cnpj = preg_replace(['/\D/', '/\./', '/\-/'], '', $request->cnpj);
        $validarCNPJ = $this->validarCNPJ($cnpj);

        if ($validarCNPJ->getStatusCode() != 200) {
            return $validarCNPJ;
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
        $cnpj = strtoupper(preg_replace('/[^A-Z0-9]/', '', $cnpj));
        if (preg_match('/^(.)\1{13}$/', $cnpj)) {
            return response()->json(['message' => 'O CNPJ informado é inválido (caracteres repetidos).'], 400);
        }
        if (strlen($cnpj) != 14) {
            return response()->json(['message' => 'O CNPJ informado não possui 14 caracteres.'], 400);
        }

        // Conversão Alphanumérica (ASCII - 48)
        $valores = [];
        for ($i = 0; $i < 14; $i++) {
            $char = $cnpj[$i];
            $valores[] = is_numeric($char) ? (int)$char : ord($char) - 48;
        }

        // Pesos de Cálculo
        $pesos1 = [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        $pesos2 = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];

        // 1º Dígito Verificador (DV)
        $soma1 = 0;
        for ($i = 0; $i < 12; $i++) {
            $soma1 += $valores[$i] * $pesos1[$i];
        }
        $resto1 = $soma1 % 11;
        $dv1 = ($resto1 < 2) ? 0 : 11 - $resto1;

        if ($valores[12] !== $dv1) {
            return response()->json(['message' => 'O CNPJ informado não é válido (1º dígito verificador).'], 400);
        }

        // 2º Dígito Verificador (DV)
        $soma2 = 0;
        for ($i = 0; $i < 12; $i++) {
            $soma2 += $valores[$i] * $pesos2[$i];
        }
        $soma2 += $dv1 * $pesos2[12];
        $resto2 = $soma2 % 11;
        $dv2 = ($resto2 < 2) ? 0 : 11 - $resto2;

        if ($valores[13] !== $dv2) {
            return response()->json(['message' => 'O CNPJ informado não é válido (2º dígito verificador).'], 400);
        }

        return response()->json(['message' => 'O CNPJ informado é válido.'], 200);
    }
}
