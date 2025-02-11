<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;

class ProjectController extends Controller
{

    
    public function indexAll(Request $request)
    {
        $projects = Project::get();

        return response()->json([
            'message' => 'Proyectos obtenidos con éxito',
            'projects' => $projects,
        ], 200);
    }

    public function index(Request $request)
    {
        if ($request->is('api/*')){
            $user = $request->user();
    
            if (!$user) {
                return response()->json(['message' => 'Usuario no autenticado'], 401);
            }
            $projects = Project::where('user_id', $user->id)->get();
    
            return response()->json([
                'message' => 'Proyectos obtenidos con éxito',
                'projects' => $projects,
            ], 200);
        }
        $search = $request->input('search');
        
        if ($search) {
            $projects = Project::where('nombre', 'like', "%$search%")->get();
        } else {
            $projects = Project::all();
        }

        return view('projects.index', compact('projects'));
    }

    



    public function show($id)
    {
        $project = Project::find($id);

        if (!$project) {
            return response()->json([
                'message' => 'Proyecto no encontrado',
            ], 404);
        }
        
        return view('projects.show', compact('project'));
    }

    public function create()
    {
        return view('projects.create');
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

        return response()->json(['success'=> 'Proyecto creado con éxito', 'id'=> $project->id]);

    }
    
    public function edit(Project $project)
{
    if (request()->expectsJson()) {
        $project->update(request()->all());

        return response()->json([
            'success' => true,
            'message' => 'Proyecto actualizado correctamente'
        ]);
    }

    return view('projects.edit', compact('project'));
}


    public function update(Request $request, $id)
    {
        $project = Project::find($id);

    if (!$project) {
        return response()->json([
            'message' => 'Proyecto no encontrado'
        ], 404);
    }

    $validatedData = $request->validate([
        'nombre'   => 'required|string|max:255',
        'html_code'=> 'nullable|string',
        'css_code' => 'nullable|string',
        'js_code'  => 'nullable|string',
    ]);

    $project->update($validatedData);

    return response()->json([
        'success' => true,
        'message' => 'Proyecto actualizado con éxito',
        'project' => $project,
    ], 200);
    // $project = Project::find($id);

    //     if (!$project) {
    //         return response()->json([
    //             'message' => 'Proyecto no encontrado',
    //         ], 404);
    //     }

    //     $validatedData = $request->validate([
    //         'nombre' => 'required|string|max:255',
    //         'html_code' => 'nullable|string',
    //         'css_code' => 'nullable|string',
    //         'js_code' => 'nullable|string',
    //     ]);

    //     $project->update($validatedData);

    //     return redirect()->route('projects.index')
    //                      ->with('success', 'Proyecto actualizado con éxito');
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

        return redirect()->route('projects.index')
        ->with('success', 'Proyecto eliminado con éxito');
    }
}
