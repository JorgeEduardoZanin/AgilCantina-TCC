<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;


class AuthController extends Controller
{
    public function login(Request $request)
{
    $credentials = $request->only('email', 'password');

    try {
        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Usuário ou senha inválidos.'], 401);
        }

        $user = auth()->user();

        if ($user->role_id == 1) { 
            $cantina = $user->cantina;
            
            if ($cantina && $cantina->status == 'desapproved') {
                return response()->json(['error' => 'Sua cantina não foi aprovada pela administracao, para mais informacoes contate o suporte.'], 403);
            }
        }

        if (!$user->hasVerifiedEmail()) {
            return response()->json(['error' => 'Email não verificado.'], 403);
        }
      
        if ($user->role_id == 1) { 
            $cantina = $user->cantina;
            
            if ($cantina && $cantina->status !== 'approved') {
                return response()->json(['error' => 'Sua cantina ainda não foi aprovada pelo administrador.'], 403);
            }
        }

    } catch (JWTException $e) {
        return response()->json(['error' => 'Não foi possível criar o token.'], 500);
    }

    if($user->role_id==1){
        return response()->json([
            'token' => $token,
            'user_id' => $user->id,
            'role_id' => $user->role_id,
            'cantina_id' => $user->cantina->id       ], 200); 
    }

    // Retornar token e ID do usuário no corpo da resposta
    return response()->json([
        'token' => $token,
        'user_id' => $user->id,
        'role_id' => $user->role_id,
        
    ], 200); // Código de status HTTP 200 para sucesso
}
 
    public function logout()
    {
        auth()->logout();

        return response()->json(['msg' => 'Logout realizado com sucesso!']);
    }

    public function refresh()
    {
        try {
            $token = JWTAuth::refresh(JWTAuth::getToken());
            return response()->json(['token' => $token]);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Não foi possível atualizar o token.'], 500);
        }
    }

    public function me()
    {
        return response()->json(auth()->user());
    }
}
