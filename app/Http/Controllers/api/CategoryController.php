<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // GET /api/categories
    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $categories = Category::latest()->get();

        return CategoryResource::collection($categories);
    }

    // POST /api/categories
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active'   => 'boolean',
        ]);

        // slug auto-generate dari model (booted() event)
        $category = Category::create($validated);

        return (new CategoryResource($category))
            ->response()
            ->setStatusCode(201);
    }

    // GET /api/categories/{id}
    public function show(string $id): CategoryResource|JsonResponse
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        return new CategoryResource($category);
    }

    // PUT/PATCH /api/categories/{id}
    public function update(Request $request, string $id): CategoryResource|JsonResponse
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $validated = $request->validate([
            'name'        => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'is_active'   => 'boolean',
        ]);

        $category->update($validated);

        return new CategoryResource($category);
    }

    // DELETE /api/categories/{id}
    public function destroy(string $id): JsonResponse
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $category->delete();

        return response()->json(['message' => 'Category deleted'], 200);
    }
}
