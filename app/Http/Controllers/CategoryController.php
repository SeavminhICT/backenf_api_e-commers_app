<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Add image validation
    ]);

 if($request->hasFile('image')){
        $image = $request->file('image');
        $imagePath = Storage::disk('public')->put('categories', $image);
    } else {
        $imagePath = null; // Handle case where no image is uploaded
    }

    $category = Category::create([
        'name' => $request->name,
        'image' => $imagePath, // Make sure this column exists in the database
    ]);

    return response()->json([
        'message' => "Category created successfully",
        'category' => $category,
    ], 200);
}


public function index()
{
    $categories = Category::all();

    // Convert image path to full URL
    foreach ($categories as $category) {
        if ($category->image) {
            $category->image = asset('storage/' . $category->image);
        }
    }

    return response()->json([
        'Success' => true,
        'message' => 'Categories retrieved successfully',
        'categories' => $categories
    ], 200);
}


    public function update(Request $request,$id){
        $cate = Category::find($id);
        if(!$cate){
            return response()->json([
                'message' => 'Category not found',
                'data' => $cate,
            ], 404);

        }
        $cate->update($request->all());
        return response()->json([
            'message' => 'Category updated successfully',
            'category' => $cate
        ], 200);

    }

    public function destroy($id){
        $cate = Category::find($id);
        if(!$cate){
            return response()->json([
                'message' => 'Category not found',
            ], 404);
        }
        $cate->delete();
        return response()->json([
            'message' => 'Category deleted successfully',
        ], 200);
    }
}
