<?php

namespace Modules\Orders\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Couriers\Entities\Courier;
use Modules\Customers\Entities\Customer;
use Modules\Restaurants\Entities\Restaurant;

/**
 * Modules\Orders\Entities\Order
 *
 * @property int $id
 * @property int $customer_id ID покупателя
 * @property array $content Содержимое заказа (id товаров и их количество)
 * @property int $restaurant_id ID ресторана, у которого сделан заказ
 * @property string $address Адрес доставки заказа
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Modules\Customers\Entities\Customer $customer
 * @property-read \Modules\Restaurants\Entities\Restaurant $restaurant
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Orders\Entities\Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Orders\Entities\Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Orders\Entities\Order query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Orders\Entities\Order whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Orders\Entities\Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Orders\Entities\Order whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Orders\Entities\Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Orders\Entities\Order whereRestaurantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Orders\Entities\Order whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int|null $courier_id ID курьера, которы должен доставить заказ
 * @property string $status Статус заказа
 * @property-read \Modules\Couriers\Entities\Courier|null $courier
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Orders\Entities\Order whereCourierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Orders\Entities\Order whereStatus($value)
 */
class Order extends Model
{
    public const STATUS_CREATED = 'created';
    public const STATUS_PAID = 'paid';
    public const STATUS_COOKING = 'cooking';
    public const STATUS_COOKED = 'cooked';
    public const STATUS_DELIVERING = 'delivering';
    public const STATUS_DELIVERED = 'delivered';

    protected $fillable = [
        'customer_id',
        'content',
        'restaurant_id',
        'address',
        'courier_id',
        'status',
    ];

    protected $casts = [
        'content' => 'array',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function courier()
    {
        return $this->belongsTo(Courier::class);
    }
}
