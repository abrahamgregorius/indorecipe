<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RecipeController extends Controller
{
    public function index() {
        $recipes = Recipe::get();

        return response()->json(
            $recipes->map(function($recipe) {
                return [
                    "id" => $recipe->id,
                    "title" => $recipe->title,
                    "slug" => $recipe->slug,
                    "ingredients" => $recipe->ingredients,
                    "method" => $recipe->method,
                    "tips" => $recipe->tips,
                    "energy" => $recipe->energy . " kcal",
                    "carbohydrate" => $recipe->carbohydrate . "g",
                    "protein" => $recipe->protein . "g",
                    "thumbnail" => asset($recipe->thumbnail),
                    "created_at" => $recipe->created_at,
                    "author" => $recipe->user->username,
                    "ratings_avg" => round($recipe->ratings()->avg('rating'), 1),
                    "category" => $recipe->category,
                ];
            })
        );
    }

    public function show($short) {
        $recipe = Recipe::where('slug', $short)->first();

        return response()->json([
            'id' => $recipe->id,
            "title" => $recipe->title,
            "slug" => $recipe->slug,
            "ingredients" => $recipe->ingredients,
            "method" => $recipe->method,
            "tips" => $recipe->tips,
            "energy" => $recipe->energy . " kcal",
            "carbohydrate" => $recipe->carbohydrate . "g",
            "protein" => $recipe->protein . "g",
            "thumbnail" => asset($recipe->thumbnail),
            "created_at" => $recipe->created_at,
            "author" => $recipe->user->username,
            "ratings_avg" => round($recipe->ratings()->avg('rating'), 1),
            "category" => $recipe->category,
            "comments" => $recipe->comments->map(function($comment) {
                return [
                    "id" => $comment->id,
                    "comment" => $comment->comment,
                    "created_at" => $comment->created_at,
                    "comment_author" => $comment->user->username
                ];
            })
        ]);
    }

    public function top() {
        $recipes = Recipe::get()->map(function($recipe) {
            return [
                "id" => $recipe->id,
                "title" => $recipe->title,
                "slug" => $recipe->slug,
                "ingredients" => $recipe->ingredients,
                "method" => $recipe->method,
                "tips" => $recipe->tips,
                "energy" => $recipe->energy . " kcal",
                "carbohydrate" => $recipe->carbohydrate . "g",
                "protein" => $recipe->protein . "g",
                "thumbnail" => asset($recipe->thumbnail),
                "created_at" => $recipe->created_at,
                "author" => $recipe->user->username,
                "ratings_avg" => round($recipe->ratings()->avg('rating'), 1),
                "category" => $recipe->category,
            ];
        });

        $data = $recipes->sortByDesc('ratings_avg')->values()->take(3);

        return response()->json([
            "best_recipes" => $data
        ]);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'category_id' => 'required|exists:categories,id',
            'energy' => 'required|numeric',
            'carbohydrate' => 'required|numeric',
            'protein' => 'required|numeric',
            'ingredients' => 'required',
            'method' => 'required',
            'tips' => 'required',
            'thumbnail' => 'nullable|image|mimes:png,jpg,jpeg',
        ]);

        if($validator->fails()) {
            return response()->json([
                'message' => 'Invalid field',
                'errors' => $validator->errors()
            ], 422);
        }

        Recipe::create($request->all());

        return response()->json([
            'message' => "Recipe created successful"
        ]);
    }

}
