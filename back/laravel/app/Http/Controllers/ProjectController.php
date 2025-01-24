<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    // Listar todos los proyectos
    public function index()
    {
        $projects = Project::with('users', 'puntuacions')->get();
        return response()->json($projects, 200);
    }

    // Obtener un proyecto específico
    public function show($id)
    {
        $project = Project::with('users', 'puntuacions')->find($id);

        if (!$project) {
            return response()->json(['message' => 'Proyecto no encontrado'], 404);
        }

        return response()->json($project, 200);
    }

    // Crear un nuevo proyecto
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
            'html_code' => 'required|string',
            'css_code' => 'required|string',
            'js_code' => 'required|string',
        ]);

        $project = Project::create($validatedData);

        return response()->json([
            'message' => 'Proyecto creado exitosamente',
            'project' => $project,
        ], 201);
    }

    // Actualizar un proyecto existente
    public function update(Request $request, $id)
    {
        $project = Project::find($id);

        if (!$project) {
            return response()->json(['message' => 'Proyecto no encontrado'], 404);
        }

        $validatedData = $request->validate([
            'nombre' => 'sometimes|string|max:255',
            'user_id' => 'sometimes|exists:users,id',
            'html_code' => 'sometimes|string',
            'css_code' => 'sometimes|string',
            'js_code' => 'sometimes|string',
        ]);

        $project->update($validatedData);

        return response()->json([
            'message' => 'Proyecto actualizado exitosamente',
            'project' => $project,
        ], 200);
    }

    // Eliminar un proyecto
    public function destroy($id)
    {
        $project = Project::find($id);

        if (!$project) {
            return response()->json(['message' => 'Proyecto no encontrado'], 404);
        }

        $project->delete();

        return response()->json(['message' => 'Proyecto eliminado exitosamente'], 200);
    }
}
