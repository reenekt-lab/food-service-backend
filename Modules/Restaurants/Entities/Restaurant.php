<?php

namespace Modules\Restaurants\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Restaurants\Entities\Restaurant
 *
 * @property int $id
 * @property string $name Название
 * @property string $description Описание
 * @property string $address Адрес
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Restaurants\Entities\Restaurant newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Restaurants\Entities\Restaurant newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Restaurants\Entities\Restaurant query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Restaurants\Entities\Restaurant whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Restaurants\Entities\Restaurant whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Restaurants\Entities\Restaurant whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Restaurants\Entities\Restaurant whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Restaurants\Entities\Restaurant whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Restaurants\Entities\Restaurant whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Restaurant extends Model
{
    protected $fillable = [
        'name',
        'description',
        'address',
    ];
}
