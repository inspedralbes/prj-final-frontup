<?php

namespace App\Http\Controllers;

use App\Models\NivellUsuaris;
use App\Http\Requests\StoreLevelRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NivellUsuariController extends Controller
{
    public function index()
    {
        return NivellUsuaris::with('user')->latest()->get();
    }
    
    public function store(Request $request)
    {
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'initial_html' => 'nullable|string',
        'initial_css' => 'nullable|string',
        'initial_js' => 'nullable|string',
        'expected_html' => 'nullable|string',
        'expected_css' => 'nullable|string',
        'expected_js' => 'nullable|string',
    ]);

    $level = NivellUsuaris::create([
        'title' => $validated['title'],
        'description' => $validated['description'],
        'initial_html' => $validated['initial_html'] ?? '',
        'initial_css' => $validated['initial_css'] ?? '',
        'initial_js' => $validated['initial_js'] ?? '',
        'expected_html' => $validated['expected_html'] ?? '',
        'expected_css' => $validated['expected_css'] ?? '',
        'expected_js' => $validated['expected_js'] ?? '',
        'user_id' => Auth::id(),
    ]);

    return response()->json([
        'message' => 'Nivel creado exitosamente',
        'level' => $level
    ], 201);
    }

    public function show($id)
    { 
    $level = NivellUsuaris::with('user')->findOrFail($id);
    return $level;
    }

    public function update(Request $request, NivellUsuaris $level)
    {
        $this->authorize('update', $level);

        $level->update($request->validated());

        return response()->json([
            'message' => 'Nivel actualizado exitosamente',
            'level' => $level
        ]);
    }

    public function destroy(NivellUsuaris $level)
    {
        $this->authorize('delete', $level);

        $level->delete();

        return response()->json([
            'message' => 'Nivel eliminado exitosamente'
        ]);
    }
}