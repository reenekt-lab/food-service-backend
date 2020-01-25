<?php

namespace Modules\Customers\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Customers\Entities\Customer;

class CustomerPolicy
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
     * @param  Customer $customer
     * @return mixed
     */
    public function view(User $user, Customer $customer)
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
     * @param  Customer $customer
     * @return mixed
     */
    public function update(User $user, Customer $customer)
    {
        return true;
    }

    /**
     * Determine whether the user can delete the models user.
     *
     * @param User $user
     * @param  Customer $customer
     * @return mixed
     */
    public function delete(User $user, Customer $customer)
    {
        return true;
    }

    /**
     * Determine whether the user can restore the models user.
     *
     * @param User $user
     * @param  Customer $customer
     * @return mixed
     */
    public function restore(User $user, Customer $customer)
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the models user.
     *
     * @param User $user
     * @param  Customer $customer
     * @return mixed
     */
    public function forceDelete(User $user, Customer $customer)
    {
        return true;
    }
}
