<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Validator;

class AuthController extends Controller
{
    /**
     * Registra um novo usuário.
     *
     * Requisição esperada:
     * {
     *   "name": "Seu Nome",
     *   "email": "email@exemplo.com",
     *   "password": "senha123",
     *   "password_confirmation": "senha123"
     * }
     *
     * Retorna os dados do usuário criado e um token de autenticação.
     */
    public function register(Request $request)
    {
        // Validação dos dados do usuário
        $validator = Validator::make($request->all(), [
            'name'                  => 'required|string|max:255',
            'email'                 => 'required|email|unique:users,email',
            'password'              => 'required|string|min:6|confirmed', // 'confirmed' espera que haja o campo password_confirmation
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        // Cria o novo usuário (a senha é armazenada de forma segura usando hash)
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Gera um token de acesso para o usuário (exemplo utilizando Laravel Sanctum)
        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'message' => 'Usuário registrado com sucesso',
            'user'    => $user,
            'token'   => $token,
        ], 201);
    }

    /**
     * Faz o login do usuário.
     *
     * Requisição esperada:
     * {
     *   "email": "email@exemplo.com",
     *   "password": "senha123"
     * }
     *
     * Caso as credenciais estejam corretas, retorna um token de acesso e os dados do usuário.
     */
    public function login(Request $request)
    {
        // Validação dos dados de login
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        // Tenta autenticar o usuário com as credenciais fornecidas
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Email ou senha incorretos'
            ], 401);
        }

        // Recupera o usuário autenticado
        $user = Auth::user();

        // Gera um token para o usuário autenticado, se estiver usando token-based authentication
        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'message' => 'Login realizado com sucesso',
            'user'    => $user,
            'token'   => $token,
        ], 200);
    }

    /**
     * Faz o logout do usuário.
     * 
     * Requisição esperada: Basta enviar o token no header da requisição.
     * Esse método revoga o token atual do usuário autenticado.
     */
    public function logout(Request $request)
    {
        // Revoga o token atual para efetuar o logout (para Laravel Sanctum)
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout realizado com sucesso'
        ], 200);
    }

    /**
     * Atualiza a senha do usuário autenticado.
     *
     * Requisição esperada:
     * {
     *   "current_password": "senhaAtual",
     *   "new_password": "novaSenha123",
     *   "new_password_confirmation": "novaSenha123"
     * }
     *
     * Primeiro valida se a senha atual está correta e, em seguida, atualiza para a nova senha.
     */
    public function updatePassword(Request $request)
    {
        // Validação dos dados para atualização da senha
        $validator = Validator::make($request->all(), [
            'current_password'      => 'required|string',
            'new_password'          => 'required|string|min:6|confirmed', // 'confirmed' espera new_password_confirmation
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();

        // Verifica se a senha atual corresponde à armazenada
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'message' => 'Senha atual incorreta'
            ], 401);
        }

        // Atualiza a senha com a nova senha criptografada
        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'message' => 'Senha atualizada com sucesso'
        ], 200);
    }
}
