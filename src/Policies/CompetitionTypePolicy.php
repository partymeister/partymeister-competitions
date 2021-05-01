<?php

namespace Partymeister\Competitions\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Motor\Backend\Models\User;
use Partymeister\Competitions\Models\CompetitionType;

class CompetitionTypePolicy
{
    use HandlesAuthorization;

    /**
     * Perform pre-authorization checks.
     *
     * @param \Motor\Backend\Models\User $user
     * @param string $ability
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
     * @param \Motor\Backend\Models\User $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('competition_types.read');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param \Motor\Backend\Models\User $user
     * @param \Partymeister\Competitions\Models\CompetitionType $competitionType
     * @return mixed
     */
    public function view(User $user, CompetitionType $competitionType)
    {
        return $user->hasPermissionTo('competition_types.read');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param \Motor\Backend\Models\User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo('competition_types.write');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param \Motor\Backend\Models\User $user
     * @param \Partymeister\Competitions\Models\CompetitionType $competitionType
     * @return mixed
     */
    public function update(User $user, CompetitionType $competitionType)
    {
        return $user->hasPermissionTo('competition_types.write');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param \Motor\Backend\Models\User $user
     * @param \Partymeister\Competitions\Models\CompetitionType $competitionType
     * @return mixed
     */
    public function delete(User $user, CompetitionType $competitionType)
    {
        return $user->hasPermissionTo('competition_types.delete');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param \Motor\Backend\Models\User $user
     * @param \Partymeister\Competitions\Models\CompetitionType $competitionType
     * @return mixed
     */
    public function restore(User $user, CompetitionType $competitionType)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param \Motor\Backend\Models\User $user
     * @param \Partymeister\Competitions\Models\CompetitionType $competitionType
     * @return mixed
     */
    public function forceDelete(User $user, CompetitionType $competitionType)
    {
        //
    }
}
