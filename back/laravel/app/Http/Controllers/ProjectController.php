<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{


    public function indexAll(Request $request)
    {
        $query = Project::query();
        
        $query->where('statuts', 0);
        
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('nombre', 'like', "%{$search}%");
        }
        
        if ($request->has('sort')) {
            $sort = $request->input('sort');
            if ($sort === 'date_asc') {
                $query->orderBy('created_at', 'asc');
            } elseif ($sort === 'date_desc') {
                $query->orderBy('created_at', 'desc');
            } elseif ($sort === 'popular') {
                $query->withCount('likes')->orderBy('likes_count', 'desc');
            }
        }
        
        $projects = $query->paginate(9);

        $projects->getCollection()->transform(function ($project) {
            $project->preview_url = $this->generatePreviewUrl($project);
            return $project;
        });
    
        return response()->json($projects, 200);
    }  

    protected function generatePreviewUrl($project)
    {
    return route('projects.preview', ['id' => $project->id]);
    }

    public function preview($id)
    {
    $project = Project::findOrFail($id);
    
    return view('projects.preview', compact('project'));
    }
    
    public function index(Request $request)
    {
        if ($request->is('api/*')) {
            $user = $request->user();

            if (!$user) {
                return response()->json(['message' => 'Usuario no autenticado'], 401);
            }

            $query = Project::where('user_id', $user->id);

            if ($request->has('search')) {
                $search = $request->input('search');
                $query->where('nombre', 'like', "%{$search}%");
            }
            
            if ($request->has('sort')) {
                $sort = $request->input('sort');
                if ($sort === 'date_asc') {
                    $query->orderBy('created_at', 'asc');
                } elseif ($sort === 'date_desc') {
                    $query->orderBy('created_at', 'desc');
                } elseif ($sort === 'popular') {
                    $query->withCount('likes')->orderBy('likes_count', 'desc');
                }
            }

            $projects = $query->paginate(9);

            $projects->getCollection()->transform(function ($project) {
                $project->preview_url = $this->generatePreviewUrl($project);
                return $project;
            });

            return response()->json($projects, 200);
        }

        $query = Project::query();

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('nombre', 'like', "%{$search}%");
        }
        
        if ($request->has('sort')) {
            $sort = $request->input('sort');
            if ($sort === 'date_asc') {
                $query->orderBy('created_at', 'asc');
            } elseif ($sort === 'date_desc') {
                $query->orderBy('created_at', 'desc');
            }
        }

        $projects = $query->paginate(9);

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

        return response()->json($project);
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
        if ($request->is('api/*')) {
        return response()->json(['success' => 'Proyecto creado con éxito', 'id' => $project->id]);
        }
        $projects = Project::all();
        return view('projects.index', compact('projects'));
    }

    public function edit(Project $project)
    {
        if (request()->expectsJson()) {
            $userId = Auth::id();
            if($userId == $project->user_id){
                $project->update(request()->all());
    
                return response()->json([
                    'user' => $userId,
                    'user_project' => $project->user_id,
                    'success' => true,
                    'message' => 'Proyecto actualizado correctamente'
                ]);

            }
            else{
                return response()->json([
                    'user' => $userId,
                    'user_project' => $project->user_id,
                    'success' => false,
                    'message' => 'Para actualizar debes ser el creador'
                ]);
            }
        }

        return view('projects.edit', compact('project'));
    }


    public function update(Request $request, $id)
    {
        if ($request->is('api/*')) {
            $project = Project::find($id);
            $userId = Auth::id();
        
            if (!$project) {
                return response()->json([
                    'message' => 'Proyecto no encontrado'
                ], 404);
            }

            $validatedData = $request->validate([
                'nombre'    => 'required|string|max:255',
                'html_code' => 'nullable|string',
                'css_code'  => 'nullable|string',
                'js_code'   => 'nullable|string',
                'statuts'   => 'nullable|boolean',
            ]);

            
            if($userId == $project->user_id){
                $project->update($validatedData);
    
                return response()->json([
                    'user' => $userId,
                    'user_project' => $project->user_id,
                    'success' => true,
                    'message' => 'Proyecto actualizado con éxito',
                    'project' => $project,
                ], 200);

            }
            else{
                return response()->json([
                    'user' => $userId,
                    'user_project' => $project->user_id,
                    'success' => false,
                    'message' => 'Para actualizar debes ser el creador'
                ]);
            }
        }

        $project = Project::find($id);

        if (!$project) {
            return response()->json([
                'message' => 'Proyecto no encontrado',
            ], 404);
        }

        $validatedData = $request->validate([
            'nombre'    => 'required|string|max:255',
            'html_code' => 'nullable|string',
            'css_code'  => 'nullable|string',
            'js_code'   => 'nullable|string',
            'statuts'   => 'nullable|boolean',
        ]);

        $project->update($validatedData);

        return redirect()->route('projects.index')->with('success', 'Proyecto actualizado con éxito');
    }


    public function destroy(Request $request, $id)
    {
        $project = Project::find($id);
        $userId = Auth::id();

        if (!$project) {
            return response()->json([
                'message' => 'Proyecto no encontrado',
            ], 404);
        }

        if ($request->is('api/*')) {
            if($userId == $project->user_id){
                $project->delete();
                return response()->json(['success', 'Proyecto eliminado']);
            }
            else{
                return response()->json(['error', 'No tienes permiso para eliminar este proyecto']);
            }
        }
        $project->delete();
        $projects = Project::all();
        return redirect()->route('projects.index')->with('success', 'Proyecto eliminado con éxito');
    }
}
