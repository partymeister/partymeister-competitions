<?php

namespace Partymeister\Competitions\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Motor\Backend\Models\User;
use Partymeister\Competitions\Models\VoteCategory;

class VoteCategoryPolicy
{
    use HandlesAuthorization;

    /**
     * Perform pre-authorization checks.
     *
     * @param  \Motor\Backend\Models\User  $user
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
     * @param  \Motor\Backend\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('vote_categories.read');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \Motor\Backend\Models\User  $user
     * @param  \Partymeister\Competitions\Models\VoteCategory  $voteCategory
     * @return mixed
     */
    public function view(User $user, VoteCategory $voteCategory)
    {
        return $user->hasPermissionTo('vote_categories.read');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \Motor\Backend\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo('vote_categories.write');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \Motor\Backend\Models\User  $user
     * @param  \Partymeister\Competitions\Models\VoteCategory  $voteCategory
     * @return mixed
     */
    public function update(User $user, VoteCategory $voteCategory)
    {
        return $user->hasPermissionTo('vote_categories.write');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \Motor\Backend\Models\User  $user
     * @param  \Partymeister\Competitions\Models\VoteCategory  $voteCategory
     * @return mixed
     */
    public function delete(User $user, VoteCategory $voteCategory)
    {
        return $user->hasPermissionTo('vote_categories.delete');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \Motor\Backend\Models\User  $user
     * @param  \Partymeister\Competitions\Models\VoteCategory  $voteCategory
     * @return mixed
     */
    public function restore(User $user, VoteCategory $voteCategory)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \Motor\Backend\Models\User  $user
     * @param  \Partymeister\Competitions\Models\VoteCategory  $voteCategory
     * @return mixed
     */
    public function forceDelete(User $user, VoteCategory $voteCategory)
    {
        //
    }
}
