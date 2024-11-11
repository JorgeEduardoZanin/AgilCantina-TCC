<?php

namespace App\Http\Controllers;

use App\Models\Cantina;
use App\Models\User;


class AdminController extends Controller
{

    public function approveCanteen($cantina_id)
    {
        
     
    $cantina = Cantina::findOrFail($cantina_id);

  
    $user = User::findOrFail($cantina->user_id); 
    if (!$user->hasVerifiedEmail()) {
        return response()->json(['message' => 'Email ainda não verificado.'], 401);
    }

  
    $cantina->status = 'approved';
    $cantina->save();

    return response()->json([
        'message' => 'Cantina aprovada com sucesso!'
    ], 200);
    }

    public function disapproveCanteen($cantina_id)
    {
        
      
    $cantina = Cantina::findOrFail($cantina_id);


    $cantina->status = 'desapproved';
    $cantina->save();

    return response()->json([
        'message' => 'Cantina nao aprovada.'
    ], 200);
    }
}
