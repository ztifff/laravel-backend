<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    // List all products
    public function index()
    {
        $products = Product::with('category')->get();
        return response()->json($products);
    }

    // Show a single product
    public function show($id)
    {
        $product = Product::with('category')->find($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }
        return response()->json($product);
    }

    // Create a new product
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string',
            'description' => 'nullable|string',
            'price'       => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'image'       => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['name', 'description', 'price', 'category_id']);

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . Str::slug($request->name) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $filename); // store in public/images
            $data['image'] = '/images/' . $filename;
        }

        $product = Product::create($data);

        return response()->json([
            'message' => 'Product created successfully',
            'product' => $product
        ], 201);
    }

    // Update a product
    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $request->validate([
            'name'        => 'sometimes|required|string',
            'description' => 'nullable|string',
            'price'       => 'sometimes|required|numeric',
            'category_id' => 'sometimes|required|exists:categories,id',
            'image'       => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['name', 'description', 'price', 'category_id']);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image && file_exists(public_path($product->image))) {
                unlink(public_path($product->image));
            }

            $image = $request->file('image');
            $filename = time() . '_' . Str::slug($request->name ?? $product->name) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $filename);
            $data['image'] = '/images/' . $filename;
        }

        $product->update($data);

        return response()->json([
            'message' => 'Product updated successfully',
            'product' => $product
        ]);
    }

    // Delete a product
    public function destroy($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        if ($product->image && file_exists(public_path($product->image))) {
            unlink(public_path($product->image));
        }

        $product->delete();

        return response()->json(['message' => 'Product deleted successfully']);
    }
}
