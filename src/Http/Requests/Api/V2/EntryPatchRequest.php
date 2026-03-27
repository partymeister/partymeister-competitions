<?php

namespace Partymeister\Competitions\Http\Requests\Api\V2;

class EntryPatchRequest extends EntryPostRequest
{
    public function rules(): array
    {
        return collect(parent::rules())
            ->mapWithKeys(fn ($rule, $key) => [
                $key => 'sometimes|'.$rule,
            ])
            ->all();
    }
}
