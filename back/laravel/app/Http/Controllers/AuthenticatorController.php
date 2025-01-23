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
        // Validación de las credenciales de inicio de sesión
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Intentar autenticar al usuario
        if (Auth::attempt($credentials)) {
            // Obtener el usuario autenticado
            $user = Auth::user();

            // Crear un token de acceso
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'status' => 'success',
                'message' => 'User authenticated successfully',
                'token' => $token,
                'user' => $user,
            ]);
        }

        // Si las credenciales no son válidas
        return response()->json(['status' => 'error', 'message' => 'Invalid credentials'], 401);
    }

    /**
     * Logout user and invalidate token.
     */
    public function logout(Request $request)
    {
        // Revocar el token del usuario actual
        $request->user()->currentAccessToken()->delete();

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
        // Validación de los datos de entrada para el registro
        $data = $request->validate([
            'username' => 'required|string|min:3',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:4',
            'avatar' => 'nullable|string', // Avatar es opcional
            'nivel' => 'integer|min:1', // Nivel es opcional
        ]);

        try {
            // Crear un nuevo usuario con los datos validados
            $user = new User();
            $user->name = $data['username'];
            $user->email = $data['email'];
            $user->password = Hash::make($data['password']); // Encriptar la contraseña
            $user->avatar = $data['avatar'] ?? 'default-avatar.png'; // Avatar predeterminado si no se proporciona
            $user->nivel = $data['nivel'] ?? 1; // Nivel predeterminado si no se proporciona
            $user->save();

            // Crear un token para el usuario
            $token = $user->createToken('auth_token')->plainTextToken;
            Auth::login($user); // Iniciar sesión automáticamente

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
