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

         
            if (!$user->hasVerifiedEmail()) {
                return response()->json(['error' => 'Email não verificado.'], 403);
            }

        } catch (JWTException $e) {
            return response()->json(['error' => 'Não foi possível criar o token.'], 500);
        }

        return response()->json(['token' => $token]);
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
