<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;

class ProjectController extends Controller
{

    //Mostrar tots els projectes publics al front
    public function indexAll(Request $request)
    {
        $projects = Project::where('statuts', 0)->get();

        return response()->json([
            'message' => 'Proyectos públicos obtenidos con éxito',
            'projects' => $projects,
            'status' => 200,
        ], 200);
    }

    //Mostra tots els projectes d'un usuari
    public function index(Request $request)
    {
        if ($request->is('api/*')) {
            $user = $request->user();

            if (!$user) {
                return response()->json(['message' => 'Usuario no autenticado', 'status' => 401], 401);
            }
            $projects = Project::where('user_id', $user->id)->get();

            return response()->json([
                'message' => 'Proyectos obtenidos con éxito',
                'projects' => $projects,
                'status' => 200,
            ], 200);
        }
        $search = $request->input('search');

        if ($search) {
            $projects = Project::where('nombre', 'like', "%$search%")->where('statuts', 0) ->get();
        } else {
            $projects = Project::where('statuts', 0)->get();
        }

        return view('projects.index', compact('projects'));
    }

    //Mostrar Tots els projectes tant com públic i privats (Per al crud)
    public function indexAllProjects(Request $request)
    {
        $search = $request->input('search');
        
        if ($search) {
            $projects = Project::where('nombre', 'like', "%$search%")->get(); 
        } else {
            $projects = Project::all();
        }
    
        // Debugging: Mostra el nombre de projectes trobats
        dd($projects->count(), $projects);
    
        return view('projects.all', compact('projects'));
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
            'statuts' => 'required|boolean',
        ]);

        $project = Project::create($validatedData);
        if ($request->is('api/*')) {
            return response()->json(['success' => 'Proyecto creado con éxito', 'id' => $project->id, 'status' => 201], 201);
        }
        $projects = Project::all();
        return view('projects.index', compact('projects'));
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


    public function update(Request $request, Project $project)
    {
        $validatedData = $request->validate([
            'nombre'   => 'required|string|max:255',
            'html_code' => 'nullable|string',
            'css_code' => 'nullable|string',
            'js_code'  => 'nullable|string',
            'statuts' => 'required|boolean',
        ]);

        $project->update($validatedData);

        if ($request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Proyecto actualizado con éxito',
                'project' => $project,
                'status' => 200,
            ], 200);
        }

        return redirect()->route('projects.index')->with('success', 'Projecte actualitzat correctament.');
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
        if ($request->is('api/*')) {
        return response()->json(['success', 'Proyecto eliminado']);
        }
        $projects = Project::all();
        return redirect()->route('projects.index')->with('success', 'Proyecto eliminado con éxito');

    }
}

