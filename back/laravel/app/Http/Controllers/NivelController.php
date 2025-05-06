<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Nivel;

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
        'userId' => 'required|integer|exists:users,id',
        'nivel' => 'required|integer',
    ]);

    try {
        $user = \App\Models\User::findOrFail($request->userId);
        $user->nivel = $request->nivel;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Nivel HTML actualizado correctamente',
            'user' => $user,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error al actualizar el nivel HTML',
            'error' => $e->getMessage(),
        ], 500);
    }
    }    
}
