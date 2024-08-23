<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CantinaController extends Controller
{

    public function dashboard()
    {
        // Lógica para exibir o dashboard da cantina
        return response()->json(['message' => 'Bem-vindo ao dashboard da cantina']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
