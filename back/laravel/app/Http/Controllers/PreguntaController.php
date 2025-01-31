<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Pregunta;
use Illuminate\Http\Request;

class PreguntaController extends Controller
{
    
    public function getPreguntasPorNivel($nivelId)
{
    if (!is_numeric($nivelId)) {
        return response()->json(['error' => 'Nivel ID inválido'], 400);
    }

    $pregunta = Pregunta::where('nivel_id', $nivelId)->first();

    if (!$pregunta) {
        return response()->json(['message' => 'No hay preguntas para este nivel'], 404);
    }

    return response()->json([
        'question' => $pregunta->pregunta, 
        'correctAnswer' => $pregunta->resp_correcta
    ]);
}


}