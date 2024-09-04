<?php

namespace App\Http\Controllers;

use App\Models\Cantina;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class CantinaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = Cantina::all();
        return response()->json($users); 
    }

    
    public function store(Request $request)
    {
        try {
            // Validação dos dados de entrada
            $validatedData = $request->validate([
                // Validação dos campos de usuário
                'name' => 'required|string|max:255',
                'cpf' => 'required|string|max:14|unique:users,cpf',
                'adress' => 'required|string|max:255',
                'telephone' => 'required|string|max:15',
                'date_of_birth' => 'required|date',
                'email' => 'required|string|email|max:255|unique:users,email',
                'password' => 'required|string|min:8|confirmed',
                'img' => 'nullable|string',
                
                // Validação dos campos de cantina
                'corporate_reason' => 'required|string|max:255|unique:cantinas,corporate_reason',
                'cnpj' => 'required|string|max:18|unique:cantinas,cnpj',
                'cell_phone' => 'required|string|max:15',
                'state' => 'required|string|max:2',
                'city' => 'required|string|max:255',
                'neighborhood' => 'required|string|max:255',
                'cep' => 'required|string|max:9',
                'name_of_person_responsible' => 'required|string|max:255',
                'phone_of_responsible' => 'required|string|max:15',
                'description' => 'nullable|string',
                'opening_hours' => 'nullable|string',
                'open' => 'required|boolean',
            ]);
    
            // Criação do usuário
            $user = User::create([
                'name' => $validatedData['name'],
                'cpf' => $validatedData['cpf'],
                'adress' => $validatedData['adress'],
                'telephone' => $validatedData['telephone'],
                'date_of_birth' => $validatedData['date_of_birth'],
                'img' => $validatedData['img'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'role_id' => 1, // ID da Role Dono de Cantina
            ]);
    
            // Criação da cantina associada ao usuário
            $cantina = Cantina::create([
                'corporate_reason' => $validatedData['corporate_reason'],
                'cnpj' => $validatedData['cnpj'],
                'cell_phone' => $validatedData['cell_phone'],
                'state' => $validatedData['state'],
                'city' => $validatedData['city'],
                'neighborhood' => $validatedData['neighborhood'],
                'cep' => $validatedData['cep'],
                'name_of_person_responsible' => $validatedData['name_of_person_responsible'],
                'phone_of_responsible' => $validatedData['phone_of_responsible'],
                'description' => $validatedData['description'],
                'opening_hours' => $validatedData['opening_hours'],
                'open' => $validatedData['open'],
                'user_id' => $user->id, // Associando a cantina ao usuário criado
            ]);
    
            // Enviar email de verificação
            $user->sendEmailVerificationNotification();
    
            return response()->json([
                'message' => 'Cantina criada com sucesso!',
                'user' => $user,
                'cantina' => $cantina
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Erro de validação',
                'errors' => $e->errors()
            ], 422);
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);
        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update($request->all());
      
        return response()->json($user->fresh());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(['msg' =>'Usuario deletado com sucesso!']);
    }
}
