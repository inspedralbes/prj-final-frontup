<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
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
     * Update user level.
     */

    public function updateLevel(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }

        $request->validate([
            'nivel' => 'required|integer|min:1',
            'nivel_css' => 'required|integer|min:1',
            'nivel_js' => 'required|integer|min:1'
        ]);

        $user->nivel = $request->nivel;
        $user->save();

        return response()->json(['message' => 'Nivel actualizado', 'nivel' => $user->nivel]);
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
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'avatar' => $request->avatar,
            'nivel' => $request->nivel ?? 1,
            'nivel_css' => $request->nivel_css ?? 11,
            'nivel_js' => $request->nivel_js ?? 21,
        ]);

        return redirect()->route('users.index');
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
