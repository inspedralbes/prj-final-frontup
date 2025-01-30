<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
