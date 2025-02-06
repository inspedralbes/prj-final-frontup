<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Get the authenticated user.
     */
    public function getUser(Request $request)
    {
        $user = $request->user();

        if (!filter_var($user->avatar, FILTER_VALIDATE_URL)) {
            $user->avatar = 'https://api.multiavatar.com/' . urlencode($user->name) . '.png';
        }

        return response()->json([
            'status' => 'success',
            'user' => $user,
        ]);
    }

    /**
     * Update the authenticated user's avatar.
     */
    public function updateAvatar(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'avatar' => 'required|string',
        ]);

        try {
            $user->avatar = $data['avatar'];
            $user->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Avatar actualizado correctamente',
                'user' => $user,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'No se pudo actualizar el avatar: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update user levels.
     */
    public function updateLevels(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }

        $request->validate([
            'nivel_html' => 'required|integer|min:1',
            'nivel_css' => 'required|integer|min:1',
            'nivel_js' => 'required|integer|min:1',
        ]);

        $user->nivel_html = $request->nivel_html;
        $user->nivel_css = $request->nivel_css;
        $user->nivel_js = $request->nivel_js;
        $user->save();

        return response()->json([
            'message' => 'Niveles actualizados',
            'nivel_html' => $user->nivel_html,
            'nivel_css' => $user->nivel_css,
            'nivel_js' => $user->nivel_js,
        ]);
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:4',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('users.index')->with('success', 'Usuario creado correctamente.');
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->update($request->except(['password']) + [
            'password' => $request->password ? Hash::make($request->password) : $user->password,
        ]);

        return redirect()->route('users.index');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index');
    }
}
