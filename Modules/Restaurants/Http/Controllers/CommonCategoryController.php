<?php

namespace Modules\Restaurants\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Modules\Restaurants\Entities\CommonCategory;
use Modules\Restaurants\Http\Requests\CommonCategoryCreateRequest;
use Modules\Restaurants\Http\Requests\CommonCategoryUpdateRequest;
use Modules\Restaurants\Transformers\CommonCategoryCollection;
use Modules\Restaurants\Transformers\CommonCategory as CommonCategoryResource;
use Throwable;

class CommonCategoryController extends Controller
{
    public function __construct()
    {
        // TODO
//        $this->authorizeResource(CommonCategory::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return CommonCategoryCollection
     */
    public function index()
    {
        $resource = CommonCategory::with('restaurants')->paginate();
        return new CommonCategoryCollection($resource);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CommonCategoryCreateRequest $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(CommonCategoryCreateRequest $request)
    {
        // TODO crud
        throw new Exception('NOT IMPLEMENTED');
        $common_category = new CommonCategory;
        $common_category->fill($request->all());
        $common_category->saveOrFail();

        return response()->json([
            'message' => __('restaurants::common_category.created'),
        ], 201);
    }

    /**
     * Show the specified resource.
     *
     * @param CommonCategory $common_category
     * @return CommonCategoryResource
     */
    public function show(CommonCategory $common_category)
    {
        return new CommonCategoryResource($common_category->load('restaurants'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CommonCategoryUpdateRequest $request
     * @param CommonCategory $common_category
     * @return JsonResponse
     */
    public function update(CommonCategoryUpdateRequest $request, CommonCategory $common_category)
    {
        // TODO crud
        throw new Exception('NOT IMPLEMENTED');
        $common_category->update($request->all());
        return response()->json([
            'message' => __('restaurants::common_category.updated'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param CommonCategory $common_category
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(CommonCategory $common_category)
    {
        // TODO crud
        throw new Exception('NOT IMPLEMENTED');
        $common_category->delete();

        // Возможно в будущем будет заменено на http code 204
        return response()->json([
            'message' => __('restaurants::common_category.deleted'),
        ]);
    }
}
