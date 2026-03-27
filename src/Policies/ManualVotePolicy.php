<?php

namespace Partymeister\Competitions\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Motor\Admin\Models\User;
use Partymeister\Competitions\Models\ManualVote;

class ManualVotePolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability)
    {
        if ($user->hasRole('SuperAdmin')) {
            return true;
        }
    }

    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('manual_votes.read');
    }

    public function view(User $user, ManualVote $manualVote)
    {
        return $user->hasPermissionTo('manual_votes.read');
    }

    public function create(User $user)
    {
        return $user->hasPermissionTo('manual_votes.write');
    }

    public function update(User $user, ManualVote $manualVote)
    {
        return $user->hasPermissionTo('manual_votes.write');
    }

    public function delete(User $user, ManualVote $manualVote)
    {
        return $user->hasPermissionTo('manual_votes.delete');
    }

    public function restore(User $user, ManualVote $manualVote)
    {
        //
    }

    public function forceDelete(User $user, ManualVote $manualVote)
    {
        //
    }
}
