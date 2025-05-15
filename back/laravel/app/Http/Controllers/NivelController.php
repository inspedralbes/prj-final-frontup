<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Nivel;
use App\Models\User;

class NivelController extends Controller
{
    public function getPregunta($id)
    {
        $pregunta = Nivel::find($id);
        
        if (!$pregunta) {
            return response()->json(['message' => 'No hay pregunta para este nivel'], 404);
        }

        return response()->json([
            'language' => 'html',
            'question' => $pregunta->pregunta,
            'resp_correcta' => $pregunta->resp_correcta
        ]);
    }

    public function verificarRespuesta(Request $request, $id)
    {
        $request->validate([
            'respuesta_usuario' => 'required|string'
        ]);

        $pregunta = Nivel::find($id);

        if (!$pregunta) {
            return response()->json(['error' => 'Pregunta no encontrada'], 404);
        }

        $correcta = trim(strtolower($request->respuesta_usuario)) === 
                   trim(strtolower($pregunta->resp_correcta));

        return response()->json([
            'correct' => $correcta,
            'message' => $correcta ? 'Respuesta correcta' : 'Respuesta incorrecta'
        ]);
    }

    public function actualizarNivel(Request $request)
{
    $request->validate([
        'nivel' => 'required|integer'
    ]);

    try {
        $user = $request->user(); 
        $user->nivel = $request->nivel;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Nivel CSS actualizado correctamente',
            'user' => $user,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error al actualizar el nivel CSS',
            'error' => $e->getMessage(),
        ], 500);
    }
}
}
