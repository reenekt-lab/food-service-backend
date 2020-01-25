<?php

namespace Modules\Couriers\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Couriers\Entities\Courier;

class CourierPolicy
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
     * @param  Courier $courier
     * @return mixed
     */
    public function view(User $user, Courier $courier)
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
     * @param  Courier $courier
     * @return mixed
     */
    public function update(User $user, Courier $courier)
    {
        return true;
    }

    /**
     * Determine whether the user can delete the models user.
     *
     * @param User $user
     * @param  Courier $courier
     * @return mixed
     */
    public function delete(User $user, Courier $courier)
    {
        return true;
    }

    /**
     * Determine whether the user can restore the models user.
     *
     * @param User $user
     * @param  Courier $courier
     * @return mixed
     */
    public function restore(User $user, Courier $courier)
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the models user.
     *
     * @param User $user
     * @param  Courier $courier
     * @return mixed
     */
    public function forceDelete(User $user, Courier $courier)
    {
        return true;
    }
}
