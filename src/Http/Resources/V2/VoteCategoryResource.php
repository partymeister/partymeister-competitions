<?php

namespace Partymeister\Competitions\Http\Resources\V2;

use Motor\Core\Http\Resources\V2\BaseResource;
use Partymeister\Competitions\Models\VoteCategory;

/**
 * @mixin VoteCategory
 */
class VoteCategoryResource extends BaseResource
{
    public function toArray($request): array
    {
        return [
            'id' => (int) $this->id,
            'name' => $this->name,
            'points' => (int) $this->points,
            'has_negative' => (bool) $this->has_negative,
            'has_comment' => (bool) $this->has_comment,
            'has_special_vote' => (bool) $this->has_special_vote,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
