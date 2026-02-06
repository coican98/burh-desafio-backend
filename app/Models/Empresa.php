<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $table = 'empresas';

    protected $fillable = [
        'nome',
        'descricao',
        'cnpj',
        'plano',
    ];

    public function vagas()
    {
        return $this->hasMany(Vaga::class);
    }
}
