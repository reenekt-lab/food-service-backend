<?php

namespace Modules\Restaurants\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\RestaurantManagers\Entities\RestaurantManager;
use Modules\Restaurants\Entities\Restaurant;

class RestaurantPolicy
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

        if ($user instanceof RestaurantManager) {
            return null; // go to next checks
        }

        return false; // deny
    }

    /**
     * Determine whether the user can view any models users.
     *
     * @param User|RestaurantManager $user
     * @return mixed
     */
    public function viewAny($user)
    {
        return false;
    }

    /**
     * Determine whether the user can view the models user.
     *
     * @param User|RestaurantManager $user
     * @param  Restaurant $restaurant
     * @return mixed
     */
    public function view($user, Restaurant $restaurant)
    {
        return $user->restaurant_id == $restaurant->id;
    }

    /**
     * Determine whether the user can create models users.
     *
     * @param User|RestaurantManager $user
     * @return mixed
     */
    public function create($user)
    {
        return false;
    }

    /**
     * Determine whether the user can update the models user.
     *
     * @param User|RestaurantManager $user
     * @param  Restaurant $restaurant
     * @return mixed
     */
    public function update($user, Restaurant $restaurant)
    {
        return true;
    }

    /**
     * Determine whether the user can delete the models user.
     *
     * @param User|RestaurantManager $user
     * @param  Restaurant $restaurant
     * @return mixed
     */
    public function delete($user, Restaurant $restaurant)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the models user.
     *
     * @param User|RestaurantManager $user
     * @param  Restaurant $restaurant
     * @return mixed
     */
    public function restore($user, Restaurant $restaurant)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the models user.
     *
     * @param User|RestaurantManager $user
     * @param  Restaurant $restaurant
     * @return mixed
     */
    public function forceDelete($user, Restaurant $restaurant)
    {
        return false;
    }
}
