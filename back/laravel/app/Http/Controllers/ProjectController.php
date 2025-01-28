<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::all();
        
        return response()->json([
            'message' => 'Proyectos obtenidos con éxito',
            'projects' => $projects,
        ], 200);
    }

    public function show($id)
    {
        $project = Project::find($id);

        if (!$project) {
            return response()->json([
                'message' => 'Proyecto no encontrado',
            ], 404);
        }

        return response()->json([
            'message' => 'Proyecto obtenido con éxito',
            'project' => $project,
        ], 200);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
            'html_code' => 'nullable|string',
            'css_code' => 'nullable|string',
            'js_code' => 'nullable|string',
        ]);

        $project = Project::create($validatedData);

        return response()->json([
            'message' => 'Proyecto creado con éxito',
            'project' => $project,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $project = Project::find($id);

        if (!$project) {
            return response()->json([
                'message' => 'Proyecto no encontrado',
            ], 404);
        }

        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'html_code' => 'nullable|string',
            'css_code' => 'nullable|string',
            'js_code' => 'nullable|string',
        ]);

        $project->update($validatedData);

        return response()->json([
            'message' => 'Proyecto actualizado con éxito',
            'project' => $project,
        ], 200);
    }

    public function destroy($id)
    {
        $project = Project::find($id);

        if (!$project) {
            return response()->json([
                'message' => 'Proyecto no encontrado',
            ], 404);
        }

        $project->delete();

        return response()->json([
            'message' => 'Proyecto eliminado con éxito',
        ], 200);
    }
}
