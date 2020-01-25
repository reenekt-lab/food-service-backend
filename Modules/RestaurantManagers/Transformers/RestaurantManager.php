<?php

namespace Modules\RestaurantManagers\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class RestaurantManager extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
