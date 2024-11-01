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
    public function store(Request $request, int $cantina_id)
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

     
        $product = Product::create([
            'name' => $validatedData['name'],
            'price' => $validatedData['price'],
            'description' => $validatedData['description'],
            'quantity' => $validatedData['quantity'],
            'availability' => $validatedData['availability'],
            'img' => $validatedData['img'],
            'cost_price' => $validatedData['cost_price'],
            'cantina_id' => $cantina_id,

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
            return response()->json(['message' => 'Product not found'], 404);
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

        // Atualiza os dados do produto com os dados validados
        $product->update($validatedData);

        // Retorna os dados atualizados do produto
        return response()->json($product->fresh(),201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( string $cantina_id, string $product_id)
    {
        $user = Product::findOrFail($product_id);
        $user->delete();
        return response()->json(['msg' =>'Usuario deletado com sucesso!']);
    }
}
