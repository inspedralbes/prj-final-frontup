<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pregunta extends Model
{
    use HasFactory;

    protected $table = 'preguntas'; 

    protected $fillable = [
        'titulo',
        'descripcion',
        'nivel_id',
        'usuario_id',
    ]; 

    /**
     * Relación con el modelo Usuario
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    /**
     * Relación con el modelo Nivel
     */
    public function nivel()
    {
        return $this->belongsTo(Nivel::class, 'nivel_id');
    }
}
