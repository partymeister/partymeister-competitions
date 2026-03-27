<?php

namespace Partymeister\Competitions\Http\Resources\V2;

use Motor\Core\Http\Resources\V2\BaseResource;
use Partymeister\Competitions\Models\Competition;

/**
 * @mixin Competition
 */
class CompetitionResource extends BaseResource
{
    public function toArray($request): array
    {
        return [
            'id'                       => (int) $this->id,
            'name'                     => $this->name,
            'competition_type'         => new CompetitionTypeResource($this->whenLoaded('competition_type')),
            'has_prizegiving'          => (bool) $this->has_prizegiving,
            'upload_enabled'           => (bool) $this->upload_enabled,
            'voting_enabled'           => (bool) $this->voting_enabled,
            'sort_position'            => (int) $this->sort_position,
            'prizegiving_sort_position' => (int) $this->prizegiving_sort_position,
            'vote_categories'          => VoteCategoryResource::collection($this->whenLoaded('vote_categories')),
            'option_groups'            => OptionGroupResource::collection($this->whenLoaded('option_groups')),
            'created_at'               => $this->created_at?->toIso8601String(),
            'updated_at'               => $this->updated_at?->toIso8601String(),
        ];
    }
}
