<?php

namespace Partymeister\Competitions\Http\Resources\V2;

use Motor\Core\Http\Resources\V2\BaseResource;
use Partymeister\Competitions\Models\Vote;

/**
 * @mixin Vote
 */
class VoteResource extends BaseResource
{
    public function toArray($request): array
    {
        return [
            'id' => (int) $this->id,
            'competition_id' => (int) $this->competition_id,
            'entry_id' => (int) $this->entry_id,
            'visitor_id' => $this->visitor_id ? (int) $this->visitor_id : null,
            'vote_category' => new VoteCategoryResource($this->whenLoaded('vote_category')),
            'points' => (int) $this->points,
            'special_vote' => (bool) $this->special_vote,
            'comment' => $this->comment,
            'ip_address' => $this->ip_address,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
