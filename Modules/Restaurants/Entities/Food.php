<?php

namespace Modules\Restaurants\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Restaurants\Entities\Food
 *
 * @property int $id
 * @property string $name Название
 * @property string $description Описание
 * @property float $cost Цена за единицу
 * @property int $restaurant_id ID ресторана
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Modules\Restaurants\Entities\Restaurant $restaurant
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Restaurants\Entities\Food newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Restaurants\Entities\Food newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Restaurants\Entities\Food query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Restaurants\Entities\Food whereCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Restaurants\Entities\Food whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Restaurants\Entities\Food whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Restaurants\Entities\Food whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Restaurants\Entities\Food whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Restaurants\Entities\Food whereRestaurantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Restaurants\Entities\Food whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Food extends Model
{
    protected $fillable = [
        'name',
        'description',
        'cost',
        'restaurant_id',
    ];

    public function restaurant()
    {
        return $this->hasOne(Restaurant::class, 'id', 'restaurant_id');
    }
}
