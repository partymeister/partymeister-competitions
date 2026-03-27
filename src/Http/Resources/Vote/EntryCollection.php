<?php

namespace Partymeister\Competitions\Http\Resources\Vote;

use Illuminate\Http\Request;
use Motor\Admin\Http\Resources\BaseCollection;

class EntryCollection extends BaseCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
