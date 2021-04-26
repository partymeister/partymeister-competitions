<?php

namespace Partymeister\Competitions\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class LiveVoteCollection extends ResourceCollection
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
