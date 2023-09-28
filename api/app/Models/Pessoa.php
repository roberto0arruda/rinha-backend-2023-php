<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pessoa extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $casts = [
        'stack' => 'array',
    ];

    protected $fillable = [
        'id',
        'apelido',
        'nome',
        'nascimento',
        'stack',
    ];
}
