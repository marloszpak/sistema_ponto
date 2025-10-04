<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Batida extends Model
{
    protected $table = 'batidas';

    protected $fillable = [
        'funcionario_id',
        'tipo',
        'registrado_em',
        'observacao'
    ];

    protected $casts = [
        'registrado_em' => 'datetime',
    ];

    public function funcionario(): BelongsTo
    {
        return $this->belongsTo(Funcionario::class);
    }
}
