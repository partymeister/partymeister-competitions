<?php

namespace Partymeister\Competitions\Http\Resources\V2;

use Motor\Core\Http\Resources\V2\BaseCollection;

class OptionGroupCollection extends BaseCollection
{
    public $collects = OptionGroupResource::class;

    public function toArray($request): array
    {
        return parent::toArray($request);
    }
}
