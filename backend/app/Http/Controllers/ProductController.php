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

    public function index(Request $request, string $cantina_id)
    {
    $validatedData = $request->validate([
        'filter' => 'nullable|string|max:255',
    ]);
    
    $query = Product::where('cantina_id', $cantina_id);

    if (!empty($validatedData['filter'])) {
        $query->where('name', 'like', '%' . $validatedData['filter'] . '%');
    }

    $produtos = $query->get();

    return response()->json($produtos);
    }


    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'description' => 'required|string|max:150',
            'quantity' => 'required|integer',
            'availability' => 'required|boolean',
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

    public function update(Request $request, string $cantina_id, string $product_id)
    {

        $validatedData = $request->validate([
            'name' => 'string|max:255|nullable',
            'price' => 'numeric|nullable',
            'description' => 'string|max:150|nullable',
            'quantity' => 'integer|nullable',
            'availability' => 'boolean|nullable',
            'img' => 'string',
            'cost_price' => 'numeric|nullable',
        ]);

        $product = Product::where('cantina_id', $cantina_id)->findOrFail($product_id);
        $user = auth()->user();
        if($product->cantina_id != $user->cantina->id){
            return response()->json(['msg' => 'Esse produto nao pertece a sua cantina.'],404);
        }
        $product->update($validatedData);

        return response()->json($product->fresh(),201);
    }

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
    $request->validate([
        'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    $prod = Product::findOrFail($id);
    
    if ($prod->hasMedia('imagesProduct')) {
        $prod->getFirstMedia('imagesProduct')->delete();
    }

    $media = $prod->addMediaFromRequest('image')->toMediaCollection('imagesProduct');


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
          
            $imageUrl = $prod->getFirstMediaUrl('imagesProduct'); 
        } else {
     
            return response()->json(['Usuario sem imagem']);
        }
    
        return response()->json([
            'image_url' => $imageUrl,
            'prod_id' => $prod->id
        ],201);
    }
}
