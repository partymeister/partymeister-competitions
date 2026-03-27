<?php

namespace Partymeister\Competitions\Http\Resources\V2;

use Motor\Core\Http\Resources\V2\BaseCollection;

class AccessKeyCollection extends BaseCollection
{
    public $collects = AccessKeyResource::class;

    public function toArray($request): array
    {
        return parent::toArray($request);
    }
}
