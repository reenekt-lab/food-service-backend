<?php

namespace Modules\FoodCatalog\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\FoodCatalog\Entities\Tag
 *
 * @property int $id
 * @property string $name Название тега
 * @property string|null $description Описание тега
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\FoodCatalog\Entities\Tag newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\FoodCatalog\Entities\Tag newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\FoodCatalog\Entities\Tag query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\FoodCatalog\Entities\Tag whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\FoodCatalog\Entities\Tag whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\FoodCatalog\Entities\Tag whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\FoodCatalog\Entities\Tag whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\FoodCatalog\Entities\Tag whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Tag extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];
}
