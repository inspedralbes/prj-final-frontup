<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Pregunta;
use Illuminate\Http\Request;

class PreguntaController extends Controller
{
   
    public function getPreguntas($language, $id) 
{
    if (!is_numeric($id)) {  
        return response()->json(['error' => 'ID inválido'], 400);
    }

    $lenguajesDisponibles = ['html', 'css', 'js']; 
    if (!in_array($language, $lenguajesDisponibles)) {
        return response()->json(['error' => 'Lenguaje no soportado'], 400);
    }

    $pregunta = Pregunta::where('id', $id) 
                        ->where('language', $language)
                        ->first();

    if (!$pregunta) {
        return response()->json(['message' => 'No hay preguntas para este nivel'], 404);
    }

    return response()->json([
        'language' => $language,
        'question' => $pregunta->pregunta,
        'resp_correcta' => $pregunta->resp_correcta
    ]);
}


public function verificarRespuesta(Request $request)
{
    $request->validate([
        'language' => 'required|string|in:html,css,js',
        'id' => 'required|integer',
        'respuesta_usuario' => 'required|string'
    ]);

    $pregunta = Pregunta::where('id', $request->id)
                        ->where('language', $request->language)
                        ->first();

    if (!$pregunta) {
        return response()->json(['error' => 'Pregunta no encontrada'], 404);
    }

    if (trim(strtolower($request->respuesta_usuario)) === trim(strtolower($pregunta->resp_correcta))) {
        return response()->json(['correct' => true, 'message' => 'Respuesta correcta']);
    }

    return response()->json(['correct' => false, 'message' => 'Respuesta incorrecta']);
}

public function actualizarNivel(Request $request)
{
    $request->validate([
        'userId' => 'required|integer|exists:users,id',
        'campo' => 'required|string|in:nivel,nivel_css,nivel_js',
        'nivel' => 'required|integer',
    ]);

    try {
        $user = \App\Models\User::findOrFail($request->userId);
        $user->{$request->campo} = $request->nivel;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Nivel actualizado correctamente',
            'user' => $user,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error al actualizar el nivel',
            'error' => $e->getMessage(),
        ], 500);
    }
}


}
