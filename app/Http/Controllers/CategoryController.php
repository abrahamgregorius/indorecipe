<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index() {
        $categories = Category::get();

        return response()->json([
            'categories' => $categories
        ]);
    }

    public function store(Request $request) {
        $category = Category::create([
            'name' => $request->name,
            'slug' => $request->slug,
        ]);

        return response()->json([
            'message' => 'Category created',
            'category' => $category
        ]);
    }

    public function show($short) {
        $category = Category::where('slug', $short)->first();

        if(!$category) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }

        return response()->json($category);
    }

    public function update($short, Request $request) {
        $category = Category::where('slug', $short)->first();

        if(!$category) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }

        $category->update($request->all());

        return response()->json([
            'message' => 'Category updated',
            'category' => $category
        ]);
    }

    public function destroy($short) {
        $category = Category::where('slug', $short)->first();
        
        if(!$category) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }
        
        $category->delete();

        return response()->json([
            'message' => 'Category deleted'
        ]);
    }

    public function all_names() {
        $categories = Category::get();

        return response()->json([
    
    
            "categories" => $categories->map(function($category) {
            return [
                'name' => $category->name,
            ];
        })
    ]);
    }


}
