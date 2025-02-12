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
        'id',
        'usuario_id',
        'language',
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
        return $this->belongsTo(Nivel::class, 'id');
    }

    public function language($query, $language)
    {
        return $query->where('language', $language);
    }
}
