<?php

namespace Partymeister\Competitions\Http\Resources\V2;

use Motor\Core\Http\Resources\V2\BaseResource;
use Partymeister\Competitions\Models\ManualVote;

/**
 * @mixin ManualVote
 */
class ManualVoteResource extends BaseResource
{
    public function toArray($request): array
    {
        return [
            'id' => (int) $this->id,
            'competition_id' => (int) $this->competition_id,
            'entry_id' => (int) $this->entry_id,
            'points' => (int) $this->points,
            'ip_address' => $this->ip_address,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
