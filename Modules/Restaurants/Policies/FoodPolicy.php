<?php

namespace Modules\Restaurants\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\RestaurantManagers\Entities\RestaurantManager;
use Modules\Restaurants\Entities\Food;

class FoodPolicy
{
    use HandlesAuthorization;

    /**
     * Policy "before" filter
     *
     * @param User|RestaurantManager $user
     * @param $ability
     * @return bool|null
     */
    public function before($user, $ability)
    {
        if ($user instanceof User) {
            return true; // allow
        }

        return null; // go to next checks
    }

    /**
     * Determine whether the user can view any models users.
     *
     * @param User|RestaurantManager|null $user
     * @return mixed
     */
    public function viewAny($user = null)
    {
        return true;
    }

    /**
     * Determine whether the user can view the models user.
     *
     * @param User|RestaurantManager|null $user
     * @param  Food $food
     * @return mixed
     */
    public function view($user = null, Food $food)
    {
        return true;
    }

    /**
     * Determine whether the user can create models users.
     *
     * @param User|RestaurantManager $user
     * @return mixed
     */
    public function create($user)
    {
        return $user instanceof RestaurantManager;
    }

    /**
     * Determine whether the user can update the models user.
     *
     * @param User|RestaurantManager $user
     * @param  Food $food
     * @return mixed
     */
    public function update($user, Food $food)
    {
        return $user instanceof RestaurantManager && $user->restaurant_id == $food->restaurant_id;
    }

    /**
     * Determine whether the user can delete the models user.
     *
     * @param User|RestaurantManager $user
     * @param  Food $food
     * @return mixed
     */
    public function delete($user, Food $food)
    {
        return $user instanceof RestaurantManager && $user->restaurant_id == $food->restaurant_id;
    }

    /**
     * Determine whether the user can restore the models user.
     *
     * @param User|RestaurantManager $user
     * @param  Food $food
     * @return mixed
     */
    public function restore($user, Food $food)
    {
        return $user instanceof RestaurantManager && $user->restaurant_id == $food->restaurant_id;
    }

    /**
     * Determine whether the user can permanently delete the models user.
     *
     * @param User|RestaurantManager $user
     * @param  Food $food
     * @return mixed
     */
    public function forceDelete($user, Food $food)
    {
        return false;
    }
}
