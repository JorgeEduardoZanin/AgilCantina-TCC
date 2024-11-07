<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;
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
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'cpf' => 'required|cpf',
            'telephone' => 'string|max:15|required',
            'adress' => 'string|max:255|required',
            'city' => 'string|max:255|required',
            'date_of_birth' => 'date|required',
          
        ]);


        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $user = User::create([
            'name' => $request->name,
            'surname' => $request->surname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'cpf' => $request->cpf,
            'telephone' => $request->telephone,
            'adress' => $request->adress,
            'city' => $request->city,
            'date_of_birth' => $request->date_of_birth,
            'role_id' => 3,
            

        ]);

       
        $user->sendEmailVerificationNotification();

        return response()->json([
            'message' => 'Usuário registrado com sucesso. Verifique seu email para confirmar o cadastro.',
            'user' => $user,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);
        return response()->json($user,201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,string $id)
    {
        $user = User::findOrFail($id);
        $user->update($request->all());
        
        return response()->json($user->fresh(),201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(['msg' =>'Usuario deletado com sucesso!'],201);
    }

    public function image(Request $request)
    {
    // Validação do arquivo de imagem
    $request->validate([
        'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    // Obtém o usuário autenticados
    $user = User::findOrFail(id: 2);

    // Adiciona a imagem ao perfil do usuário
    $media = $user->addMediaFromRequest('image')->toMediaCollection('images');

    // Retorna uma resposta com a URL da imagem
    return response()->json([
        'message' => 'Imagem de perfil atualizada com sucesso.',
        'image_url' => $media->getUrl(),
    ]);
    }



    public function showImage()
    {
        $modelo = User::findOrFail(2);

        // Obtém a URL da primeira imagem na coleção 'images' (substitua pelo nome da sua coleção)
        $imageUrl = $modelo->getFirstMediaUrl('images', 'thumb'); // 'thumb' pode ser o nome de uma conversão, se tiver uma

        return response()->json([
            'image_url' => $imageUrl,
        ]);
    }


}
