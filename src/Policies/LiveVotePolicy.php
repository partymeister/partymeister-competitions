<?php

namespace Partymeister\Competitions\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Motor\Admin\Models\User;
use Partymeister\Competitions\Models\LiveVote;

class LiveVotePolicy
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
        return $user->hasPermissionTo('live_votes.read');
    }

    public function view(User $user, LiveVote $liveVote)
    {
        return $user->hasPermissionTo('live_votes.read');
    }

    public function create(User $user)
    {
        return $user->hasPermissionTo('live_votes.write');
    }

    public function update(User $user, LiveVote $liveVote)
    {
        return $user->hasPermissionTo('live_votes.write');
    }

    public function delete(User $user, LiveVote $liveVote)
    {
        return $user->hasPermissionTo('live_votes.delete');
    }

    public function restore(User $user, LiveVote $liveVote)
    {
        //
    }

    public function forceDelete(User $user, LiveVote $liveVote)
    {
        //
    }
}
