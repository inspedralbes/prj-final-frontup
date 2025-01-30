<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pregunta;
use Illuminate\Http\Request;

class PreguntaController extends Controller
{
    // Obtener preguntas por nivel_lenguage y nivel_id
    public function getPreguntasPorNivel($nivelLenguage, $nivelId)
    {
        // Filtrar preguntas por nivel_lenguage y nivel_id
        $preguntas = Pregunta::where('nivel_lenguage', $nivelLenguage)
                             ->where('nivel_id', $nivelId)
                             ->get();

        // Devolver las preguntas como JSON
        return response()->json($preguntas);
    }
}