<?php

namespace Partymeister\Competitions\Http\Resources\V2;

use Motor\Core\Http\Resources\V2\BaseResource;
use Partymeister\Competitions\Models\CompetitionPrize;

/**
 * @mixin CompetitionPrize
 */
class CompetitionPrizeResource extends BaseResource
{
    public function toArray($request): array
    {
        return [
            'id' => (int) $this->id,
            'competition' => new CompetitionResource($this->whenLoaded('competition')),
            'amount' => $this->amount,
            'additional' => $this->additional,
            'rank' => (int) $this->rank,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
