<?php

namespace App\Http\Controllers;

use App\Models\Category;
use function Pest\Laravel\json;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\CategoryResource;
use App\Http\Requests\StoreCategoryRequest;

use App\Http\Requests\UpdateCategoryRequest;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::orderBy('created_at', 'desc')
            ->get();

        if ($categories->isEmpty()) {
            return response()->json([
                'errors' => ['message' => 'Categories not found'],
            ], 404);
        }

        return CategoryResource::collection($categories)
            ->response()
            ->setStatusCode(200);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        $category = Category::create([
            'name' => $request->name,
            'slug' => $request->slug,
        ]);

        return response()->json([
            'data' => new CategoryResource($category),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $idCategoryOrSlug)
    {
        $category = Category::where('id', $idCategoryOrSlug)
            ->orWhere('slug', $idCategoryOrSlug)
            ->first();

        if (!$category) {
            return response()->json([
                'errors' => ['message' => 'Category not found'],
            ], 404);
        }

        return new CategoryResource($category);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        //
    }
}
