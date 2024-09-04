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
    public function index(Request $request,string $cantina_id)
    {
        $query = $this->model->where('cantina_id', $cantina_id);

        $filter = $request->input('filter');

        if($filter){
            $query->where('name', 'like', "%$filter%");
        }
        

        $results = $query->get();

       
        return response()->json($results);

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
        ]);

     
        $product = Product::create([
            'name' => $validatedData['name'],
            'price' => $validatedData['price'],
            'description' => $validatedData['description'],
            'quantity' => $validatedData['quantity'],
            'availability' => $validatedData['availability'],
            'img' => $validatedData['img'],
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
        $user = $this->model->findOrFail($product_id);
        $user->update($request->all());
      
        return response()->json($user->fresh());
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
