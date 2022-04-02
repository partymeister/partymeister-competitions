<?php

namespace Partymeister\Competitions\Http\Resources\Profile;

use Motor\Backend\Http\Resources\BaseCollection;

class EntryCollection extends BaseCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
