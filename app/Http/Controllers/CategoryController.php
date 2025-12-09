<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    // List all categories
    public function index()
    {
        return response()->json(Category::all());
    }

    // Show a single category
    public function show($id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }
        return response()->json($category);
    }

    // Create a new category
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $category = Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name)
        ]);

        return response()->json($category, 201);
    }

    // Update an existing category
    public function update(Request $request, $id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name)
        ]);

        return response()->json($category);
    }

    // Delete a category
    public function destroy($id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $category->delete();
        return response()->json(['message' => 'Category deleted']);
    }
}
