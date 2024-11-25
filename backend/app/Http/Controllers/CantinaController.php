<?php

namespace App\Http\Controllers;

use App\Models\Cantina;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
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
    
        $query = Cantina::approved(); 
    
        if (!empty($validatedData['filter'])) {
            $query->where('canteen_name', 'like', '%' . $validatedData['filter'] . '%');
        }
    
        $cantinas = $query->get();

        return response()->json($cantinas);
    }

    public function store(Request $request)
    {
        try {
           
            $validatedData = $request->validate([
                'name' => 'string|max:255',
                'surname' => 'string|max:255',
                'email' => 'string|email|max:255|unique:users,email',
                'password' => 'string|min:8',
                'cpf' => 'cpf|unique:users,cpf',
                'telephone' => 'string|max:15|required',
                'adress' => 'string|max:255|required',
                'date_of_birth' => 'date|required',
                'img' => 'nullable|string',
                'canteen_name' => 'string|max:255',
                'corporate_reason' => 'string|max:255',
                'cnpj' => 'cnpj|unique:cantinas,cnpj',
                'cell_phone' => 'string|max:15',
                'state' => 'string|max:2',
                'city' => 'string|max:255',
                'neighborhood' => 'string|max:255',
                'cep' => 'string|max:9',
                'name_of_person_responsible' => 'string|max:255',
                'phone_of_responsible' => 'string|max:15',
                'description' => 'nullable|string',
                'opening_hours' => 'nullable|string'
            ], [
                'email.unique' => 'O e-mail informado já está em uso.',
                'cpf.unique' => 'O CPF informado já está em uso.',
                'cnpj.unique' => 'O CNPJ informado já está em uso.'
            ]);
         
            DB::beginTransaction();
           
            $user = User::create([
                'name' => $validatedData['name'],
                'surname' => $validatedData['surname'],
                'cpf' => $validatedData['cpf'],
                'adress' => $validatedData['adress'],
                'telephone' => $validatedData['telephone'],
                'date_of_birth' => $validatedData['date_of_birth'],
                'img' => $validatedData['img'] ?? null,
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'role_id' => 1, 
            ]);
 
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
                'description' => $validatedData['description'] ?? null,
                'opening_hours' => $validatedData['opening_hours'] ?? null,
                'open' => 0,
                'status' => 'pending',
                'user_id' => $user->id, 
            ]);

            $user->sendEmailVerificationNotification();

            DB::commit();

            return response()->json([
                'message' => 'Cantina criada com sucesso! Verifique seu e-mail e aguarde a aprovação de um administrador para fazer o login.',
                'user' => $user,
                'cantina' => $cantina
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Erro de validação',
                'errors' => $e->errors()
            ], 422);
        } catch (QueryException $e) {
            DB::rollBack(); 
            return response()->json([
                'message' => 'Erro ao processar a solicitação',
                'errors' => $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            DB::rollBack(); 
            return response()->json([
                'message' => 'Erro inesperado',
                'errors' => $e->getMessage()
            ], 500);
        }
    }


    public function show($id)
    {
        try {
        
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

    public function update(Request $request, string $cantina_id)
    {

        try{
        $validatedData = $request->validate([
            'name' => 'nullable|string|max:255',
            'surname' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:8',
            'telephone' => 'nullable|string|max:15',
            'adress' => 'nullable|string|max:255',
            'img' => 'nullable|string',
            
           
            'cell_phone' => 'nullable|string|max:15',
            'state' => 'nullable|string|max:2',
            'city' => 'nullable|string|max:255',
            'neighborhood' => 'nullable|string|max:255',
            'cep' => 'nullable|string|max:9',
            'name_of_person_responsible' => 'nullable|string|max:255',
            'phone_of_responsible' => 'nullable|string|max:15',
            'description' => 'nullable|string',
            'opening_hours' => 'nullable|string'
            ], [
                'email.unique' => 'O e-mail informado já está em uso.',
            ]);
       
        DB::beginTransaction();
           

        $cantina = Cantina::findOrFail($cantina_id);

        $cantina->update($validatedData);

        DB::commit();

        return response()->json([
            'message' => 'Cantina atualizada com sucesso!',
            'cantina' => $cantina->fresh(),
        ], 201);

        } catch (ValidationException $e) {
        return response()->json([
            'message' => 'Erro de validação',
            'errors' => $e->errors()
        ], 422);
        } catch (QueryException $e) {
        DB::rollBack(); 
        return response()->json([
            'message' => 'Erro ao processar a solicitação',
            'errors' => $e->getMessage()
        ], 500);
        } catch (\Exception $e) {
        DB::rollBack(); 
        return response()->json([
            'message' => 'Erro inesperado',
            'errors' => $e->getMessage()
        ], 500);
        }
    }

    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(['msg' =>'Usuario deletado com sucesso!']);
    }

    public function imageCanteen(Request $request)
{
    $request->validate([
        'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
    ]);
    $userId = auth()->user(); 
    $user = Cantina::findOrFail($userId->cantina->id);
    
    if ($user->hasMedia('imagesCanteen')) {
        $user->getFirstMedia('imagesCanteen')->delete();
    }

    $media = $user->addMediaFromRequest('image')->toMediaCollection('imagesCanteen');

    return response()->json([
        'message' => 'Imagem de perfil atualizada com sucesso.',
        'image_url' => $media->getUrl(),
        'user_id' => $user->id
    ]);
}



    public function showImageCanteen(string $id)
    {
       
        $user = Cantina::findOrFail($id);

        if ($user->hasMedia('imagesCanteen')) {
 
            $imageUrl = $user->getFirstMediaUrl('imagesCanteen'); 
        } else {
   
            return response()->json(['Usuario sem imagem']);
        }
    
        return response()->json([
            'image_url' => $imageUrl,
        ],201);
    }

}
