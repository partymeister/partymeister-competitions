<?php

namespace Partymeister\Competitions\Http\Resources\V2;

use Motor\Core\Http\Resources\V2\BaseCollection;

class OptionCollection extends BaseCollection
{
    public $collects = OptionResource::class;

    public function toArray($request): array
    {
        return parent::toArray($request);
    }
}
