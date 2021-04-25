<?php

namespace Partymeister\Competitions\Transformers\Competition;

use League\Fractal;
use Partymeister\Competitions\Models\Entry;

/**
 * Class EntryTransformer
 *
 * @package Partymeister\Competitions\Transformers\Competition
 */
class EntryTransformer extends Fractal\TransformerAbstract
{
    /**
     * Transform record to array
     *
     * @param Entry $record
     *
     * @return array
     */
    public function transform(Entry $record)
    {
        return [
            'title'             => $record->title,
            'author'            => ($record->competition->competition_type->is_anonymous ? trans('partymeister-competitions::backend/competitions.anonymized') : $record->author),
            'playlist_position' => $record->sort_position,
            'is_remote'         => (bool) $record->is_remote,
        ];
    }
}
