<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NivelJs;

class NivelJsController extends Controller
{
    public function getPregunta($id)
    {
        $pregunta = NivelJs::find($id);
        
        if (!$pregunta) {
            return response()->json(['message' => 'No hay pregunta para este nivel'], 404);
        }

        return response()->json([
            'language' => 'js',
            'question' => $pregunta->pregunta,
            'resp_correcta' => $pregunta->resp_correcta
        ]);
    }

    public function verificarRespuesta(Request $request, $id)
    {
        $request->validate([
            'respuesta_usuario' => 'required|string'
        ]);

        $pregunta = NivelJs::find($id);

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
        $user->nivel_js = $request->nivel;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Nivel JavaScript actualizado correctamente',
            'user' => $user,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error al actualizar el nivel JavaScript',
            'error' => $e->getMessage(),
        ], 500);
    }
}

}