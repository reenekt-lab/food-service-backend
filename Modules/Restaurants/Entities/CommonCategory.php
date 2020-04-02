<?php

namespace Modules\Restaurants\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Restaurants\Entities\CommonCategory
 *
 * @property int $id
 * @property string $name
 * @property string $image_url
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Restaurants\Entities\Restaurant[] $restaurants
 * @property-read int|null $restaurants_count
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Restaurants\Entities\CommonCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Restaurants\Entities\CommonCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Restaurants\Entities\CommonCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Restaurants\Entities\CommonCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Restaurants\Entities\CommonCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Restaurants\Entities\CommonCategory whereImageUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Restaurants\Entities\CommonCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Restaurants\Entities\CommonCategory whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CommonCategory extends Model
{
    protected $fillable = [
        'name',
        'image_url',
    ];

    public function restaurants()
    {
        return $this->belongsToMany(
            Restaurant::class,
            'common_categories_restaurants_pivot',
            'common_category_id',
            'restaurant_id'
        );
    }
}
