<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NivelCss extends Model
{
    use HasFactory;

    protected $table = 'nivel_css';
    
    protected $fillable = [
        'pregunta',
        'resp_correcta',
        'resp_usuario',
        'language'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    protected $attributes = [
        'language' => 'css'
    ];
}