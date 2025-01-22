<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
class AuthenticatorController extends Controller
{
    /**
     * Authenticate user and issue a token.
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'status' => 'success',
                'message' => 'User authenticated successfully',
                'token' => $token,
                'user' => $user,
            ]);
        }

        return response()->json(['status' => 'error', 'message' => 'Invalid credentials'], 401);
    }

    /**
     * Logout user and invalidate token.
     */
    public function logout()
    {
        Auth::logout();

        return response()->json([
            'status' => 'success',
            'message' => 'Logged out successfully',
        ]);
    }

    /**
     * Register a new user.
     */
    public function register(Request $request)
    {
        $data = $request->validate([
            'username' => 'required|string|min:3',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:4',
            'avatar' => 'nullable|string',
            'nivel' => 'nullable|integer|min:1',
        ], [
            'username.required' => 'El campo nombre es obligatorio',
            'email.required' => 'El campo email es obligatorio',
            'email.email' => 'El campo email debe ser una dirección válida',
            'email.unique' => 'Este email ya está registrado',
            'password.required' => 'El campo password es obligatorio',
            'password.min' => 'La contraseña debe tener al menos 4 caracteres',
        ]);

        try {
            $user = new User();
            $user->name = $data['username'];
            $user->email = $data['email'];
            $user->password = Hash::make($data['password']);
            $user->avatar = $data['avatar'] ?? 'default-avatar.png';
            $user->nivel = $data['nivel'] ?? 1;
            $user->save();

            $token = $user->createToken('auth_token')->plainTextToken;
            Auth::login($user);

            return response()->json([
                'status' => 'success',
                'message' => 'User registered successfully',
                'token' => $token,
                'user' => $user,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error during registration: ' . $e->getMessage(),
            ], 500);
        }
    }
}
