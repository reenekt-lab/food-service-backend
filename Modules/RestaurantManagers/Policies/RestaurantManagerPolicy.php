<?php

namespace Modules\RestaurantManagers\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\RestaurantManagers\Entities\RestaurantManager;

class RestaurantManagerPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models users.
     *
     * @param User $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the models user.
     *
     * @param User $user
     * @param  RestaurantManager $restaurantManager
     * @return mixed
     */
    public function view(User $user, RestaurantManager $restaurantManager)
    {
        return true;
    }

    /**
     * Determine whether the user can create models users.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the models user.
     *
     * @param User $user
     * @param  RestaurantManager $restaurantManager
     * @return mixed
     */
    public function update(User $user, RestaurantManager $restaurantManager)
    {
        return true;
    }

    /**
     * Determine whether the user can delete the models user.
     *
     * @param User $user
     * @param  RestaurantManager $restaurantManager
     * @return mixed
     */
    public function delete(User $user, RestaurantManager $restaurantManager)
    {
        return true;
    }

    /**
     * Determine whether the user can restore the models user.
     *
     * @param User $user
     * @param  RestaurantManager $restaurantManager
     * @return mixed
     */
    public function restore(User $user, RestaurantManager $restaurantManager)
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the models user.
     *
     * @param User $user
     * @param  RestaurantManager $restaurantManager
     * @return mixed
     */
    public function forceDelete(User $user, RestaurantManager $restaurantManager)
    {
        return true;
    }
}
