<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

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
    public function index(string $filter = null)
    {
        $query = $this->model->query();

        if($filter){
            $query->where('name', 'like', "%$filter%");
        }
        

        $results = $query->get();

       
        return response()->json($results);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = $this->model->find($id);


        if (!$product) {
            return response()->json(['message' => 'User not found'], 404);
        }
        
        return response()->json($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
