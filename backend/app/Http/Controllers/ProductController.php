<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Ramsey\Uuid\Type\Integer;

class ProductController extends Controller
{


    protected $model;

    public function __construct(Product $model)
    {
        $this->model = $model;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, string $cantina_id)
{
    // Validação dos dados de entrada
    $validatedData = $request->validate([
        'filter' => 'nullable|string|max:255',
    ]);
    
    // Inicia a query filtrando por cantina_id
    $query = Product::where('cantina_id', $cantina_id);

    // Aplica o filtro de nome, se estiver presente
    if (!empty($validatedData['filter'])) {
        $query->where('name', 'like', '%' . $validatedData['filter'] . '%');
    }

    // Obtenha os produtos filtrados
    $produtos = $query->get();

    // Retorne os produtos filtrados como JSON
    return response()->json($produtos);
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            // Validação dos campos de usuário
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'description' => 'required|string|max:150',
            'quantity' => 'required|integer',
            'availability' => 'required|boolean',
            'img' => 'nullable|string',
            'cost_price' => 'required|numeric',
        ]);

     
        $user = auth()->user();
        $product = Product::create([
            'name' => $validatedData['name'],
            'price' => $validatedData['price'],
            'description' => $validatedData['description'],
            'quantity' => $validatedData['quantity'],
            'availability' => $validatedData['availability'],
            'img' => $validatedData['img'],
            'cost_price' => $validatedData['cost_price'],
            'cantina_id' => $user->cantina->id,

        ]);
        
       
        return response()->json([
            'message' => 'Produto criado com sucesso!',
            'product' => $product,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = $this->model->find($id);

        if (!$product) {
            return response()->json(['message' => 'Produto nao encontrado'], 404);
        }
        
        return response()->json($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $cantina_id, string $product_id)
    {
           // Validação dos campos do produto
        $validatedData = $request->validate([
            'name' => 'string|max:255|nullable',
            'price' => 'numeric|nullable',
            'description' => 'string|max:150|nullable',
            'quantity' => 'integer|nullable',
            'availability' => 'boolean|nullable',
            'img' => 'string',
            'cost_price' => 'numeric|nullable',
        ]);

        // Verifica se o produto pertence à cantina especificada
        $product = Product::where('cantina_id', $cantina_id)->findOrFail($product_id);
        $user = auth()->user();
        if($product->cantina_id != $user->cantina->id){
            return response()->json(['msg' => 'Esse produto nao pertece a sua cantina.'],404);
        }
        // Atualiza os dados do produto com os dados validados
        $product->update($validatedData);

        // Retorna os dados atualizados do produto
        return response()->json($product->fresh(),201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $product_id)
    {   
        $product = Product::findOrFail($product_id);
        $user = auth()->user();
        if($product->cantina_id != $user->cantina->id){
            return response()->json(['msg' => 'Esse produto nao pertece a sua cantina.'],404);
        }
        $product->delete();
        return response()->json(['msg' =>'Usuario deletado com sucesso!']);
    }

    public function imageProduct(Request $request, string $id)
{
    // Validação do arquivo de imagem
    $request->validate([
        'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    $prod = Product::findOrFail($id);
    // Obtém o usuário autenticado
    

    // Verifica se o usuário já possui uma imagem na coleção e a exclui
    if ($prod->hasMedia('imagesProduct')) {
        $prod->getFirstMedia('imagesProduct')->delete();
    }

    // Adiciona a nova imagem ao perfil do usuário
    $media = $prod->addMediaFromRequest('image')->toMediaCollection('imagesProduct');

    // Retorna uma resposta com a URL da nova imagem
    return response()->json([
        'message' => 'Imagem de perfil atualizada com sucesso.',
        'image_url' => $media->getUrl(),
        'prod_id' => $prod->id
    ]);
}



    public function showImageProduct(string $id)
    {
      
        $prod= Product::findOrFail($id);

        if ($prod->hasMedia('imagesProduct')) {
            // Obtém a URL da primeira imagem na coleção 'images'
            $imageUrl = $prod->getFirstMediaUrl('imagesProduct'); // Usa a conversão 'thumb' se for configurada
        } else {
            // Se não houver imagem, define uma URL padrão ou mensagem
            return response()->json(['Usuario sem imagem']);
        }
    
        return response()->json([
            'image_url' => $imageUrl,
            'prod_id' => $prod->id
        ],201);
    }
}
