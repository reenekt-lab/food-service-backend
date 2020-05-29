<?php

namespace Modules\Restaurants\Entities;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\FoodCatalog\Entities\Category;
use Modules\FoodCatalog\Entities\Tag;
use Nwidart\Modules\Facades\Module;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;


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
 * @property-read Collection|Category[] $categories
 * @property-read Category|null $category
 * @property-read Collection|Tag[] $tags
 * @property-read int|null $categories_count
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
 * @property-read int|null $tags_count
 */
class Food extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $table = 'foods';

    protected $fillable = [
        'name',
        'description',
        'cost',
        'restaurant_id',
    ];

    /**
     * @return HasOne
     */
    public function restaurant()
    {
        return $this->hasOne(Restaurant::class, 'id', 'restaurant_id');
    }

    /**
     * @return Category|null
     */
    public function getCategoryAttribute()
    {
        if (Module::isEnabled('FoodCatalog')) {
            return $this->categories->first();
        }
        return null;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategoriesAttribute()
    {
        if (Module::isEnabled('FoodCatalog')) {
            return $this->categories()->get();
        }
        return [];
    }

    /**
     * @return BelongsToMany
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'food_category', 'food_id', 'category_id');
    }

    /**
     * @return Collection|Tag[]
     */
    public function getTagsAttribute()
    {
        if (Module::isEnabled('FoodCatalog')) {
            return $this->tags()->get();
        }
        return [];
    }

    /**
     * @return BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'food_tags', 'food_id', 'tag_id');
    }

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('main_image')
            ->singleFile();
    }
}
