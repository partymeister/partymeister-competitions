<?php

namespace Partymeister\Competitions\Http\Resources\V2;

use Motor\Core\Http\Resources\V2\BaseResource;
use Partymeister\Competitions\Models\Option;

/**
 * @mixin Option
 */
class OptionResource extends BaseResource
{
    public function toArray($request): array
    {
        return [
            'id'            => (int) $this->id,
            'name'          => $this->name,
            'sort_position' => (int) $this->sort_position,
        ];
    }
}
