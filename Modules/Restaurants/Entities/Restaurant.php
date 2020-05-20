<?php

namespace Modules\Restaurants\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Payments\Support\Account\Eloquent\HasAccount;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

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
 * @property-read \Modules\Payments\Entities\Account $account
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Restaurants\Entities\CommonCategory[] $common_categories
 * @property-read int|null $common_categories_count
 */
class Restaurant extends Model implements HasMedia
{
    use InteractsWithMedia, HasAccount;

    protected $fillable = [
        'name',
        'description',
        'address',
    ];

    public function common_categories()
    {
        return $this->belongsToMany(
            CommonCategory::class,
            'common_categories_restaurants_pivot',
            'restaurant_id',
            'common_category_id'
        );
    }

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('main_image')
            ->singleFile();
    }
}
