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
    

    public function getPendingCanteens()
    {

        $pendingCanteens = Cantina::where('status', 'pending')->get();
    
       
        if ($pendingCanteens->isEmpty()) {
            return response()->json(['message' => 'Nenhuma cantina pendente encontrada.'], 404);
        }
    
        return response()->json([
            'message' => 'Cantinas pendentes encontradas.',
            'Cantinas pendentes' => $pendingCanteens,
        ], 200);
    }
    
    public function getDesapprovedCanteens()
    {

        $desapprovedCanteens = Cantina::where('status', 'desapproved')->get();
    
     
        if ($desapprovedCanteens->isEmpty()) {
            return response()->json(['message' => 'Nenhuma cantina não aprovada encontrada.'], 404);
        }
    
        return response()->json([
            'message' => 'Cantinas não aprovadas encontradas.',
            'Cantinas não aprovadas' => $desapprovedCanteens,
        ], 200);
    }
        
}

