<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Método para registro de usuário
    public function register(Request $request)
    {
        // Validação dos dados de entrada
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6'
        ]);

        // Criação do usuário
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);

        // Criação do token
        $token = $user->createToken('auth_token')->plainTextToken;

        // Resposta JSON
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ]);
    }

    // Método para login de usuário
    public function login(Request $request)
    {
        // Validação dos dados de entrada
        $validatedData = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Verifica as credenciais
        if (!Auth::attempt($validatedData)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // Obtém o usuário autenticado
        $user = User::where('email', $validatedData['email'])->firstOrFail();

        // Criação do token
        $token = $user->createToken('auth_token')->plainTextToken;

        // Resposta JSON
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ]);
    }

    // Método para logout de usuário
    public function logout(Request $request)
    {
        // Revoga todos os tokens do usuário autenticado
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }
}
