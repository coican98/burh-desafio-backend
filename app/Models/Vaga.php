<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vaga extends Model
{
    protected $table = 'vagas';

    protected $fillable = [
        'empresa_id',
        'titulo',
        'descricao',
        'tipo',
        'salario',
        'horario',
        'status',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function candidatos()
    {
        return $this->belongsToMany(Usuario::class, 'candidaturas');
    }
}
