<?php

namespace Partymeister\Competitions\Http\Resources;

use Motor\Admin\Http\Resources\BaseCollection;

class CompetitionCollection extends BaseCollection
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
