<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

        return response()->json(['status' => 'error', 'message' => 'Credencials incorrectes'], 401);
    }


    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Logged out successfully',
        ]);
    }
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
     * Register a new user.
     */
    public function register(Request $request)
    {
        $data = $request->validate([
            'username' => 'required|string|min:3',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:4',
            'avatar' => 'nullable|string',
            'nivel' => 'integer|min:1',
        ]);

        try {
            $user = new User();
            $user->name = $data['username'];
            $user->email = $data['email'];
            $user->password = Hash::make($data['password']);
            $user->avatar = $data['avatar'] ?? 'https://api.multiavatar.com/' . urlencode($data['username']) . '.png';
            $user->nivel = $data['nivel'] ?? 1;
            $user->save();

            $token = $user->createToken('auth_token')->plainTextToken;

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


    public function updateAvatar(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'avatar' => 'required|string', // URL o nom del nou avatar
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

}
