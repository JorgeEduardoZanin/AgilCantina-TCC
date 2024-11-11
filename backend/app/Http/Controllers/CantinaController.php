<?php

namespace App\Http\Controllers;

use App\Models\Cantina;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
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
        $query = Cantina::approved(); // Aplica o escopo para pegar apenas cantinas aprovadas
    
        // Filtro opcional baseado no campo 'canteen_name' enviado no body da requisição
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
                'name' => 'string|max:255',
                'surname' => 'string|max:255',
                'email' => 'string|email|max:255|unique:users',
                'password' => 'string|min:8',
                'cpf' => 'cpf',
                'telephone' => 'string|max:15|required',
                'adress' => 'string|max:255|required',
                'date_of_birth' => 'date|required',
                'img' => 'nullable|string',
                
                // Validação dos campos de cantina
                'canteen_name' => 'string|max:255',
                'corporate_reason' => 'string|max:255',
                'cnpj' => 'cnpj',
                'cell_phone' => 'string|max:15',
                'state' => 'string|max:2',
                'city' => 'string|max:255',
                'neighborhood' => 'string|max:255',
                'cep' => 'string|max:9',
                'name_of_person_responsible' => 'string|max:255',
                'phone_of_responsible' => 'string|max:15',
                'description' => 'nullable|string',
                'opening_hours' => 'nullable|string'
               
                
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
                'open' => 0,
                'status' => 'pending',
                'user_id' => $user->id, // Associando a cantina ao usuário criado
            ]);
    
            // Enviar email de verificação
            $user->sendEmailVerificationNotification();
    
            return response()->json([
                'message' => 'Cantina criada com sucesso! Verifique seu e-mail e aguarde a aprovacao de um administrador para fazer o login, 
                 isso pode demorar cerca de uma semana. Apos isso voce podera fazer o login!',
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
    public function update(Request $request, string $cantina_id, string $product_id)
    {

           // Validação dos campos do produto
        $validatedData = $request->validate([
            'name' => 'nullable|string|max:255',
            'surname' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:8',
            'telephone' => 'nullable|string|max:15|required',
            'adress' => 'nullable|string|max:255|required',
            'img' => 'nullable|string',
            
            // Validação dos campos de cantina
            'cell_phone' => 'nullable|string|max:15',
            'state' => 'nullable|string|max:2',
            'city' => 'nullable|string|max:255',
            'neighborhood' => 'nullable|string|max:255',
            'cep' => 'nullable|string|max:9',
            'name_of_person_responsible' => 'nullable|string|max:255',
            'phone_of_responsible' => 'nullable|string|max:15',
            'description' => 'nullable|string',
            'opening_hours' => 'nullable|string'
        ]);
        
        // Verifica se o produto pertence à cantina especificada
        $cantina = Cantina::findOrFail($cantina_id);

        // Atualiza os dados da cantina com os dados validados
        $cantina->update($validatedData);

        // Retorna os dados atualizados da cantina
        return response()->json([
            'message' => 'Cantina atualizada com sucesso!',
            'cantina' => $cantina->fresh(),
        ], 201);
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
