<?php

namespace Modules\Restaurants\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class Food extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $result = parent::toArray($request);
        $result = Arr::add($result, 'main_image', $this->getFirstMediaUrl('main_image'));
        return $result;
    }
}
