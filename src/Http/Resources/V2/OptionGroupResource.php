<?php

namespace Partymeister\Competitions\Http\Resources\V2;

use Motor\Core\Http\Resources\V2\BaseResource;
use Partymeister\Competitions\Models\OptionGroup;

/**
 * @mixin OptionGroup
 */
class OptionGroupResource extends BaseResource
{
    public function toArray($request): array
    {
        return [
            'id'         => (int) $this->id,
            'name'       => $this->name,
            'type'       => $this->type,
            'options'    => OptionResource::collection($this->whenLoaded('options')),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
