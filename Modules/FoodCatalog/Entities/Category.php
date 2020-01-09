<?php

namespace Modules\FoodCatalog\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\FoodCatalog\Entities\Category
 *
 * @property int $id
 * @property string $name Название категории
 * @property string|null $description Описание категории
 * @property int|null $parent_id ID родительской категории
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Modules\FoodCatalog\Entities\Category $parent
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\FoodCatalog\Entities\Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\FoodCatalog\Entities\Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\FoodCatalog\Entities\Category query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\FoodCatalog\Entities\Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\FoodCatalog\Entities\Category whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\FoodCatalog\Entities\Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\FoodCatalog\Entities\Category whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\FoodCatalog\Entities\Category whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\FoodCatalog\Entities\Category whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Category extends Model
{
    protected $fillable = [
        'name',
        'description',
        'parent_id',
    ];

    public function parent()
    {
        return $this->hasOne(self::class, 'id', 'parent_id');
    }
}
