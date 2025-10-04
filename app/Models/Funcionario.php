<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Funcionario extends Model
{
    protected $table = 'funcionarios';
    protected $fillable = ['nome','cpf','cargo'];

    public function batidas(): HasMany
    {
        return $this->hasMany(Batida::class);
    }
}
