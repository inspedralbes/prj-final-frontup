<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Likes;
use Illuminate\Support\Facades\Auth;

class LikesController extends Controller
{
    
    public function index()
    {
        $likes = Likes::all();
        return response()->json($likes);
    }

    public function store(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
        ]);

        $userId = Auth::id();

        $existingLike = Likes::where('project_id', $request->project_id)
                            ->where('user_id', $userId)
                            ->first();

        if ($existingLike) {
            return response()->json(['message' => 'Ya has dado like a este proyecto'], 409);
        }

        $like = Likes::create([
            'project_id' => $request->project_id,
            'user_id' => $userId,
        ]);

        return response()->json($like, 201);
    }

    public function show(string $id)
    {
        $like = Likes::findOrFail($id);
        return response()->json($like);
    }

    public function destroy($projectId)
    {
        $userId = Auth::id();

        $like = Likes::where('project_id', $projectId)
                    ->where('user_id', $userId)
                    ->first();

        if (!$like) {
            return response()->json(['message' => 'Like no encontrado'], 404);
        }

        $like->delete();

        return response()->json(['message' => 'Like eliminado correctamente']);
    }

    public function checkLike($projectId)
    {
        $userId = Auth::id();

        $like = Likes::where('project_id', $projectId)
                    ->where('user_id', $userId)
                    ->exists();

        return response()->json(['hasLiked' => $like]);
    }

    public function likeCount($projectId)
    {
        $count = Likes::where('project_id', $projectId)->count();
        return response()->json(['count' => $count]);
    }

    public function userLikes()
    {
        $userId = Auth::id();
        $likes = Likes::with('project')->where('user_id', $userId)->get();

        $likes->each(function ($like) {
            if ($like->project) {
                $like->project->preview_url = $this->generatePreviewUrl($like->project);
            }
        });

        return response()->json($likes);
    }
    public function userAllLikes()
    {
        $userId = Auth::id();

        $likedProjects = Likes::where('user_id', $userId)
            ->with('project')
            ->get()
            ->pluck('project')
            ->filter() 
        ->map(function ($project) {
            $project->preview_url = $this->generatePreviewUrl($project);
            return $project;
        });

        return response()->json($likedProjects);
    }

    protected function generatePreviewUrl($project)
    {
    return route('projects.preview', ['id' => $project->id]);
    }
}