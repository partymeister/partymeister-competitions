<?php

namespace Partymeister\Competitions\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Motor\Backend\Http\Controllers\Controller;
use Partymeister\Competitions\Models\Competition;
use Partymeister\Competitions\Models\Entry;
use Partymeister\Competitions\Models\LiveVote;

/**
 * Class SyncController
 *
 * @package Partymeister\Competitions\Http\Controllers\Api
 */
class SyncController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function competition(Request $request)
    {
        $data = $request->get('data');

        if (! Arr::get($data, 'id')) {
            return response()->json('Error', 403);
        }

        $competition = Competition::find(Arr::get($data, 'id'));
        if (is_null($competition)) {
            $competition = new Competition();
        }
        $competition->competition_type_id = Arr::get($data, 'competition_type_id');
        $competition->name = Arr::get($data, 'name');
        $competition->sort_position = Arr::get($data, 'sort_position');
        $competition->prizegiving_sort_position = Arr::get($data, 'prizegiving_sort_position');
        $competition->has_prizegiving = Arr::get($data, 'has_prizegiving');
        $competition->upload_enabled = Arr::get($data, 'upload_enabled');
        $competition->voting_enabled = Arr::get($data, 'voting_enabled');
        $competition->save();

        if ($competition->id != Arr::get($data, 'id')) {
            $competition->id = Arr::get($data, 'id');
            $competition->save();
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function livevote(Request $request)
    {
        $data = $request->get('data');

        if (! Arr::get($data, 'entry_id')) {
            return response()->json('Error', 403);
        }

        $liveVote = LiveVote::first();

        if (is_null($liveVote)) {
            $liveVote = new LiveVote();
        }
        $liveVote->competition_id = Arr::get($data, 'competition_id');
        $liveVote->entry_id = Arr::get($data, 'entry_id');
        $liveVote->sort_position = Arr::get($data, 'sort_position');
        $liveVote->save();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\DiskDoesNotExist
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\FileDoesNotExist
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\FileIsTooBig
     */
    public function entry(Request $request)
    {
        $data = $request->get('data');

        if (! Arr::get($data, 'id')) {
            return response()->json('Error', 403);
        }

        $entry = Entry::find(Arr::get($data, 'id'));
        if (is_null($entry)) {
            $entry = new Entry();
        }
        $entry->competition_id = Arr::get($data, 'competition_id');
        $entry->title = Arr::get($data, 'title');
        $entry->description = Arr::get($data, 'description');
        $entry->author = Arr::get($data, 'author');
        $entry->sort_position = Arr::get($data, 'sort_position');
        $entry->status = Arr::get($data, 'status');
        $entry->is_remote = Arr::get($data, 'is_remote');
        $entry->save();

        $screenshot = Arr::get($data, 'screenshot.file_base64', null);
        if (! is_null($screenshot)) {
            file_put_contents(storage_path().'/'.Arr::get($data, 'screenshot.file_name'), base64_decode($screenshot));
            $entry->addMedia(storage_path().'/'.Arr::get($data, 'screenshot.file_name'))
                  ->toMediaCollection('screenshot', 'media');
        }

        $audio = Arr::get($data, 'audio.file_base64', null);
        if (! is_null($audio)) {
            file_put_contents(storage_path().'/'.Arr::get($data, 'audio.file_name'), base64_decode($audio));
            $entry->addMedia(storage_path().'/'.Arr::get($data, 'audio.file_name'))
                  ->toMediaCollection('audio', 'media');
        }

        if ($entry->id != Arr::get($data, 'id')) {
            $entry->id = Arr::get($data, 'id');
            $entry->save();
        }
    }
}
