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
    public function index(Request $request)
    {
        $validatedData = $request->validate([
            'filter' => 'nullable|string|max:255',
        ]);
    
        // Inicia a query de cantinas
        $query = Cantina::query();
    
        // Filtro opcional baseado no campo 'name' enviado no body da requisição
        if (!empty($validatedData['filter'])) {
            $query->where('canteen_name', 'like', '%' . $validatedData['filter'] . '%');
        }
    
        // Pegar os resultados filtrados
        $cantinas = $query->get();
    
        // Retornar os resultados filtrados
        return response()->json($cantinas);
    }

    
    public function store(Request $request)
    {
        try {
            // Validação dos dados de entrada
            $validatedData = $request->validate([
                // Validação dos campos de usuário
                'name' => 'required|string|max:255',
                'surname' => 'required|string|max:255',
                'cpf' => 'required|string|max:14|unique:users,cpf',
                'adress' => 'required|string|max:255',
                'telephone' => 'required|string|max:15',
                'date_of_birth' => 'required|date',
                'email' => 'required|string|email|max:255|unique:users,email',
                'password' => 'required|string|min:8',
                'img' => 'nullable|string',
                
                // Validação dos campos de cantina
                'canteen_name' => 'required|string|max:255|unique:cantinas,canteen_name',
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
                'surname' => $validatedData['surname'],
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
                'canteen_name' => $validatedData['canteen_name'],
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
    public function show($id)
    {
        try {
            // Encontrar a cantina pelo ID
            $cantina = Cantina::with('user')->findOrFail($id);
    
            return response()->json([
                'message' => 'Cantina encontrada com sucesso!',
                'cantina' => $cantina
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Cantina não encontrada',
                'error' => $e->getMessage()
            ], 404);
        }
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
