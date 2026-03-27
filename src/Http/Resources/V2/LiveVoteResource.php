<?php

namespace Partymeister\Competitions\Http\Resources\V2;

use Motor\Core\Http\Resources\V2\BaseResource;
use Partymeister\Competitions\Models\LiveVote;

/**
 * @mixin LiveVote
 */
class LiveVoteResource extends BaseResource
{
    public function toArray($request): array
    {
        return [
            'id' => (int) $this->id,
            'competition' => new CompetitionResource($this->whenLoaded('competition')),
            'entry' => new EntryResource($this->whenLoaded('entry')),
            'sort_position' => (int) $this->sort_position,
            'title' => $this->title,
            'author' => $this->author,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
