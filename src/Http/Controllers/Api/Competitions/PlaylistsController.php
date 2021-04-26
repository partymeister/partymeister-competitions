<?php

namespace Partymeister\Competitions\Http\Controllers\Api\Competitions;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Motor\Backend\Http\Controllers\ApiController;
use Partymeister\Competitions\Http\Resources\EntryResource;
use Partymeister\Competitions\Models\Competition;

/**
 * Class PlaylistsController
 *
 * @package Partymeister\Competitions\Http\Controllers\Api\Competitions
 */
class PlaylistsController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @param Competition $competition
     * @return bool|JsonResponse
     */
    public function index(Competition $competition)
    {
        $response = $this->checkIfCompetitionIsValid($competition);
        if ($response !== true) {
            return $response;
        }

        $data = EntryResource::collection($competition->sorted_entries);

        $data = Arr::get($data, 'data');

        $data = [
            'message' => 'Competition playlist read',
            'data'    => [
                'competition' => [
                    'name'         => $competition->name,
                    'is_anonymous' => (bool) $competition->competition_type->is_anonymous,
                ],
                'entries'     => ['data' => $data],
            ],
        ];

        return response()->json($data);
    }

    /**
     * @param $competition
     * @return bool|JsonResponse
     */
    protected function checkIfCompetitionIsValid($competition)
    {
        // Check for entries with status 0 or 2 (unchecked and needs feedback)
        if ($competition->entries()
                        ->whereIn('status', [0, 2])
                        ->count() > 0) {
            return response()->json([
                'competition' => $competition->name,
                'message'     => 'Not all entries are checked and/or disqualified!',
            ], 422);
        }

        $sort_position = 1;
        foreach ($competition->entries()
                             ->where('status', 1)
                             ->orderBy('sort_position', 'ASC')
                             ->get() as $entry) {
            if ($entry->sort_position != $sort_position) {
                return response()->json([
                    'competition' => $competition->name,
                    'message'     => 'Not all entries are correctly numbered! Check the sort positions!',
                ], 422);
            }
            $sort_position++;
        }

        if ($competition->competition_type->has_composer && $competition->entries()
                                                                        ->where('status', 1)
                                                                        ->where('composer_not_member_of_copyright_collective', false)
                                                                        ->count() > 0) {
            if ($entry->sort_position != $sort_position) {
                return response()->json([
                    'competition' => $competition->name,
                    'message'     => 'Some entries have composers registered with a copyright collective!',
                ], 422);
            }
        }

        return true;
    }
}
