<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Puntuacion extends Model
{
    protected $table = 'puntuacions';

    protected $fillable = [
        'project_id',
        'calificacion',
    ];
    public function projects(){
        return $this->belongsTo(Puntuacion::class, 'project_id');
    }
}
