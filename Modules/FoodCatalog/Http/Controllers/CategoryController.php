<?php

namespace Modules\FoodCatalog\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
//use Illuminate\Routing\Controller;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use Modules\FoodCatalog\Entities\Category;
use Modules\FoodCatalog\Http\Requests\CategoryCreateRequest;
use Modules\FoodCatalog\Http\Requests\CategoryUpdateRequest;
use Modules\FoodCatalog\Transformers\Category as CategoryResource;
use Modules\FoodCatalog\Transformers\CategoryCollection;
use Modules\Restaurants\Entities\Food;
use Throwable;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api,restaurant_manager')->except('index', 'show');
//        $this->authorizeResource(Category::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return CategoryCollection
     */
    public function index()
    {
        $resource = Category::with('parent')->paginate();
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
            'message' => __('foodcatalog::category.created'),
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
        return new CategoryResource($category->load('parent'));
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
            'message' => __('foodcatalog::category.updated'),
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
            'message' => __('foodcatalog::category.deleted'),
        ]);
    }

    /**
     * Attaches category to given food
     * @param Category $category
     * @param Food $food
     * @return JsonResponse
     */
    public function attach(Food $food, Category $category)
    {
        $food->categories()->detach();
        $food->categories()->attach($category);
        return response()->json([
            'message' => __('foodcatalog::category.attached'),
        ]);
    }

    /**
     * Detaches category to given food
     * @param Category $category
     * @param Food $food
     * @return JsonResponse
     */
    public function detach(Food $food, Category $category)
    {
        $food->categories()->detach();
        $food->categories()->detach($category);
        return response()->json([
            'message' => __('foodcatalog::category.detached'),
        ]);
    }

    /**
     * Sync categories to given food
     * @param Food $food
     * @param Request $request
     * @return JsonResponse
     */
    public function sync(Food $food, Request $request)
    {
        $categories = $request->input('categories');
        $food->categories()->sync($categories);
        return response()->json([
            'message' => __('foodcatalog::category.synced'),
        ]);
    }
}
