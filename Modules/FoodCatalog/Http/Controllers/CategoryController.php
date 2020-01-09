<?php

namespace Modules\FoodCatalog\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\FoodCatalog\Entities\Category;
use Modules\FoodCatalog\Http\Requests\CategoryCreateRequest;
use Modules\FoodCatalog\Http\Requests\CategoryUpdateRequest;
use Modules\FoodCatalog\Transformers\Category as CategoryResource;
use Modules\FoodCatalog\Transformers\CategoryCollection;
use Throwable;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return CategoryCollection
     */
    public function index()
    {
        $resource = Category::paginate();
        return new CategoryCollection($resource);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CategoryCreateRequest $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(CategoryCreateRequest $request)
    {
        $category = new Category;
        $category->fill($request->all());
        $category->saveOrFail();
        return response()->json([
            'message' => __('food-catalog::category.created'),
        ], 201);
    }

    /**
     * Show the specified resource.
     *
     * @param Category $category
     * @return CategoryResource
     */
    public function show(Category $category)
    {
        return new CategoryResource($category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CategoryUpdateRequest $request
     * @param Category $category
     * @return JsonResponse
     */
    public function update(CategoryUpdateRequest $request, Category $category)
    {
        $category->update($request->all());
        return response()->json([
            'message' => __('food-catalog::category.updated'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Category $category
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return response()->json([
            'message' => __('food-catalog::category.deleted'),
        ]);
    }
}
