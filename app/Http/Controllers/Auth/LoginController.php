<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User; // Importe o seu modelo de usuário
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Handle an incoming authentication request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws ValidationException
     */
    public function store(Request $request)
    {

        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);


        if (!Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        /** @var User $user */
        $user = Auth::user();

        if (!$user->active) {
            // Revoga todos os tokens e desloga o usuário inativo
            $user->tokens()->delete();
            Auth::guard('web')->logout();

            return response()->json(['message' => 'Usuário inativo.'], 403);
        }

        // Cria um token para o usuário. O nome 'api-token' é apenas para referência.
        $token = $user->createToken('api-token')->plainTextToken;

        // Retorna o token e os dados do usuário. O cliente deve armazenar o token.
        return response()->json([
            'token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ]);
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request): Response
    {
        // Para autenticação via token, o "logout" significa revogar o token atual.
        // Isso garante que o token não possa mais ser usado.
        // O middleware 'auth:sanctum' garante que $request->user() exista.
        $request->user()->currentAccessToken()->delete();

        return response()->noContent();
    }
}
