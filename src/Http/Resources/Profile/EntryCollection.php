<?php

namespace Partymeister\Competitions\Http\Resources\Profile;

use Motor\Admin\Http\Resources\BaseCollection;

class EntryCollection extends BaseCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
