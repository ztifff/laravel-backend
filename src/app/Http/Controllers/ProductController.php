<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Js;
use Illuminate\Routing\Controller; 
use Illuminate\Http\Request;


// use Illuminate\Http\Request;

class ProductController extends Controller
{
    
    public function index()
    {
        // get all products from the DB
        $products = Product::select('id', 'name', 'inventory', 'avg_sales', 'lead_time')->get();

        // return the products view
        return response()->json($products);
    }

    public function show($id)
{
    // Find product by ID
    $product = Product::find($id);

    // If not found, return 404 response
    if (!$product) {
        return response()->json([
            'message' => 'Product not found'
        ], 404);
    }

    // If found, return the product data as JSON
    return response()->json($product);
}

public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
        ]);

        $product = Product::create($validated);
        return response()->json($product, 201);
    }

    public function update(Request $request, Product $product)
    {
        $product->update($request->all());
        return response()->json($product, 200);
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(null, 204);
    }

    // /**
    //  * Show the form for creating a new resource.
    //  *
    //  * @return \Illuminate\Http\Response
    //  */
    // public function create()
    // {
    //     //
    // }

    // /**
    //  * Store a newly created resource in storage.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @return \Illuminate\Http\Response
    //  */
    // public function store(Request $request)
    // {
    //     //
    // }

    // /**
    //  * Display the specified resource.
    //  *
    //  * @param  \App\Models\Product  $product
    //  * @return \Illuminate\Http\Response
    //  */
    // public function show(Product $product)
    // {
    //     //
    // }

    // /**
    //  * Show the form for editing the specified resource.
    //  *
    //  * @param  \App\Models\Product  $product
    //  * @return \Illuminate\Http\Response
    //  */
    // public function edit(Product $product)
    // {
    //     //
    // }

    // /**
    //  * Update the specified resource in storage.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @param  \App\Models\Product  $product
    //  * @return \Illuminate\Http\Response
    //  */
    // public function update(Request $request, Product $product)
    // {
    //     //
    // }

    // /**
    //  * Remove the specified resource from storage.
    //  *
    //  * @param  \App\Models\Product  $product
    //  * @return \Illuminate\Http\Response
    //  */
    // public function destroy(Product $product)
    // {
    //     //
    // }
}
