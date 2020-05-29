<?php

namespace Modules\FoodCatalog\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Request;
use Modules\FoodCatalog\Entities\Tag;
use Modules\FoodCatalog\Http\Requests\TagCreateRequest;
use Modules\FoodCatalog\Http\Requests\TagUpdateRequest;
use Modules\FoodCatalog\Transformers\Tag as TagResource;
use Modules\FoodCatalog\Transformers\TagCollection;
use Modules\Restaurants\Entities\Food;
use Throwable;

class TagController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api,restaurant_manager')->except('index', 'show');
//        $this->authorizeResource(Tag::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return TagCollection
     */
    public function index()
    {
        $resource = Tag::paginate();
        return new TagCollection($resource);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param TagCreateRequest $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(TagCreateRequest $request)
    {
        $tag = new Tag;
        $tag->fill($request->all());
        $tag->saveOrFail();
        return response()->json([
            'message' => __('foodcatalog::tag.created'),
        ], 201);
    }

    /**
     * Show the specified resource.
     *
     * @param Tag $tag
     * @return TagResource
     */
    public function show(Tag $tag)
    {
        return new TagResource($tag);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param TagUpdateRequest $request
     * @param Tag $tag
     * @return JsonResponse
     */
    public function update(TagUpdateRequest $request, Tag $tag)
    {
        $tag->update($request->all());
        return response()->json([
            'message' => __('foodcatalog::tag.updated'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Tag $tag
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Tag $tag)
    {
        $tag->delete();
        return response()->json([
            'message' => __('foodcatalog::tag.deleted'),
        ]);
    }

    /**
     * Attaches tag to given food
     * @param Tag $tag
     * @param Food $food
     * @return JsonResponse
     */
    public function attach(Food $food, Tag $tag)
    {
        $food->tags()->attach($tag);
        return response()->json([
            'message' => __('foodcatalog::tag.attached'),
        ]);
    }

    /**
     * Detaches tag to given food
     * @param Tag $tag
     * @param Food $food
     * @return JsonResponse
     */
    public function detach(Food $food, Tag $tag)
    {
        $food->tags()->detach($tag);
        return response()->json([
            'message' => __('foodcatalog::tag.detached'),
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
        $tags = $request->input('tags');
        $food->tags()->sync($tags);
        return response()->json([
            'message' => __('foodcatalog::tag.synced'),
        ]);
    }
}
