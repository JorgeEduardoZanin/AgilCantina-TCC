<?php

namespace App\Http\Controllers;

use App\Models\User;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return response()->json($users); 
    }

    
    public function store(Request $request)
    {
    try {

        $validatedData = $request->validate([
            'name' => 'string|max:255|required',
            'surname' => 'string|max:255|required',
            'email' => 'string|email|max:255|unique:users,email|required',
            'password' => 'string|min:8|required',
            'cpf' => 'cpf|unique:users,cpf|required',
            'telephone' => 'string|max:15|required',
            'adress' => 'string|max:255|required',
            'date_of_birth' => 'date|required',
            'img' => 'nullable|string',
            'city' => 'string|required',

        ], [
            'email.unique' => 'O e-mail informado já está em uso.',
            'cpf.unique' => 'O CPF informado já está em uso.',
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
            'city' => $validatedData['city'],
            'role_id' => 3,
        ]);

        $user->sendEmailVerificationNotification();

        DB::commit();

        return response()->json([
            'message' => 'Usuário criado com sucesso! Verifique seu e-mail para confirmar o cadastro.',
            'user' => $user,
        ], 201);
    } catch (ValidationException $e) {
        return response()->json([
            'message' => 'Erro de validação',
            'errors' => $e->errors(),
        ], 422);
    } catch (QueryException $e) {
        DB::rollBack();
        return response()->json([
            'message' => 'Erro ao processar a solicitação',
            'errors' => $e->getMessage(),
        ], 500);
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'message' => 'Erro inesperado',
            'errors' => $e->getMessage(),
        ], 500);
    }
    }

    public function show(string $id)
    {
        $user = User::find($id);
        return response()->json($user,201);
    }

    public function update(Request $request,string $id)
    {
        $user = User::findOrFail($id);
        $user->update($request->all());
        
        return response()->json($user->fresh(),201);
    }

    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(['msg' =>'Usuario deletado com sucesso!'],201);
    }

    public function image(Request $request)
    {
    $request->validate([
        'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
    ]);
    $userId = auth()->user(); 
    $user = User::findOrFail($userId->id);
   
    if ($user->hasMedia('images')) {
        $user->getFirstMedia('images')->delete();
    }

    $media = $user->addMediaFromRequest('image')->toMediaCollection('images');

    return response()->json([
        'message' => 'Imagem de perfil atualizada com sucesso.',
        'image_url' => $media->getUrl(),
        'user_id' => $user->id
    ]);
    }

    public function showImage()
    {
        $userId = auth()->user();
        $user = User::findOrFail($userId->id);

        if ($user->hasMedia('images')) {
        
            $imageUrl = $user->getFirstMediaUrl('images'); 
        } else {
       
            return response()->json(['Usuario sem imagem']);
        }
    
        return response()->json([
            'image_url' => $imageUrl,
            'user_id' => $user->id
        ],201);
    }

    }
