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
        $level = NivellUsuaris::create([
            'title' => $request->title,
            'description' => $request->description,
            'initial_html' => $request->initial_html,
            'initial_css' => $request->initial_css,
            'initial_js' => $request->initial_js,
            'expected_html' => $request->expected_html,
            'expected_css' => $request->expected_css,
            'expected_js' => $request->expected_js,
            'user_id' => Auth::id()
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