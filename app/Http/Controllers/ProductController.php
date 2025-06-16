<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use App\Models\Category;

class ProductController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'is_featured' => 'boolean',
            'category_id' => 'required|exists:categories,id',
            'rating' => 'nullable|numeric|min:1|max:5',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = Storage::disk('public')->put('products', $image);
        }

        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'image' => $imagePath,
            'is_featured' => $request->is_featured ?? false,
            'category_id' => $request->category_id,
            'rating' => $request->rating,
        ]);

        return response()->json([
            'message' => "Product created successfully",
            'product' => $product
        ], 200);
    }

    public function index()
    {
        $categories = Category::with([
            'products' => function($query) {
                $query->select('id', 'name', 'description', 'price', 'image', 'is_featured', 'category_id', 'rating');
            }
        ])->latest()->get(['id', 'name']);

        // Group products by category
        $categories = $categories->map(function($category) {
            return [
                'id' => $category->id,
                'name' => $category->name,
                'products' => $category->products->map(function($product) {
                    return [
                        'id' => $product->id,
                        'name' => $product->name,
                        'description' => $product->description,
                        'price' => $product->price,
                        'image' => $product->image ? asset('storage/'.$product->image) : null,
                        'is_featured' => $product->is_featured,
                        'rating' => $product->rating
                    ];
                })
            ];
        });

        // Get featured products
        $featuredProducts = Product::where('is_featured', true)
            ->limit(5)
            ->get(['id', 'name', 'description', 'price', 'image', 'rating']);

        $featuredProducts = $featuredProducts->map(function($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'price' => $product->price,
                'rating' => $product->rating,
                'image' => $product->image ? asset('storage/'.$product->image) : null
            ];
        });

        return response()->json([
            'success' => true,
            'categories' => $categories,
            'featuredProducts' => $featuredProducts
        ]);
    }

    public function getProductByCategory($categoryId)
    {
        $category = Category::find($categoryId);
        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found'
            ], 404);
        }

        $products = $category->products()->get(['id', 'name', 'description', 'price', 'image', 'rating']);

        $products = $products->map(function($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'price' => $product->price,
                'rating' => $product->rating,
                'image' => $product->image ? asset('storage/'.$product->image) : null
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Products retrieved successfully',
            'category' => $category->name,
            'products' => $products
        ], 200);
    }

    public function search(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'min_price' => 'nullable|numeric',
            'max_price' => 'nullable|numeric',
            'categoryname' => 'nullable|string|max:255',
        ]);

        $products = Product::query()
            ->where('name', 'like', '%' . $request->name . '%')
            ->when($request->min_price, function ($query, $min_price) {
                return $query->where('price', '>=', $min_price);
            })
            ->when($request->max_price, function ($query, $max_price) {
                return $query->where('price', '<=', $max_price);
            })
            ->when($request->categoryname, function ($query, $categoryname) {
                return $query->whereHas('category', function ($q) use ($categoryname) {
                    $q->where('name', 'like', '%' . $categoryname . '%');
                });
            })
            ->get(['id', 'name', 'description', 'price', 'image', 'rating']);

        $products = $products->map(function($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'price' => $product->price,
                'rating' => $product->rating,
                'image' => $product->image ? asset('storage/'.$product->image) : null
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Products retrieved successfully',
            'products' => $products
        ], 200);
    }

    public function update(Request $request, $id)
{
    $product = Product::find($id);

    if (!$product) {
        return response()->json([
            'message' => 'Product not found',
        ], 404);
    }

    $request->validate([
        'name' => 'sometimes|required|string|max:255',
        'description' => 'sometimes|required|string',
        'price' => 'sometimes|required|numeric',
        'is_featured' => 'sometimes|boolean',
        'category_id' => 'sometimes|exists:categories,id',
        'rating' => 'nullable|numeric|min:1|max:5',
        'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);

    if ($request->hasFile('image')) {
        // Delete old image if exists
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $imagePath = Storage::disk('public')->put('products', $request->file('image'));
        $product->image = $imagePath;
    }

    $product->fill($request->only([
        'name',
        'description',
        'price',
        'is_featured',
        'category_id',
        'rating',
    ]));

    $product->save();

    return response()->json([
        'message' => 'Product updated successfully',
        'product' => $product,
    ], 200);
}



}

