<?php

namespace Partymeister\Competitions\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Motor\Admin\Models\User;
use Partymeister\Competitions\Models\OptionGroup;

class OptionGroupPolicy
{
    use HandlesAuthorization;

    /**
     * Perform pre-authorization checks.
     *
     * @param  \Motor\Admin\Models\User  $user
     * @param  string  $ability
     * @return void|bool
     */
    public function before(User $user, $ability)
    {
        if ($user->hasRole('SuperAdmin')) {
            return true;
        }
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param  \Motor\Admin\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('option_groups.read');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \Motor\Admin\Models\User  $user
     * @param  \Partymeister\Competitions\Models\OptionGroup  $optionGroup
     * @return mixed
     */
    public function view(User $user, OptionGroup $optionGroup)
    {
        return $user->hasPermissionTo('option_groups.read');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \Motor\Admin\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo('option_groups.write');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \Motor\Admin\Models\User  $user
     * @param  \Partymeister\Competitions\Models\OptionGroup  $optionGroup
     * @return mixed
     */
    public function update(User $user, OptionGroup $optionGroup)
    {
        return $user->hasPermissionTo('option_groups.write');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \Motor\Admin\Models\User  $user
     * @param  \Partymeister\Competitions\Models\OptionGroup  $optionGroup
     * @return mixed
     */
    public function delete(User $user, OptionGroup $optionGroup)
    {
        return $user->hasPermissionTo('option_groups.delete');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \Motor\Admin\Models\User  $user
     * @param  \Partymeister\Competitions\Models\OptionGroup  $optionGroup
     * @return mixed
     */
    public function restore(User $user, OptionGroup $optionGroup)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \Motor\Admin\Models\User  $user
     * @param  \Partymeister\Competitions\Models\OptionGroup  $optionGroup
     * @return mixed
     */
    public function forceDelete(User $user, OptionGroup $optionGroup)
    {
        //
    }
}
