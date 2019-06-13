<?php

namespace Partymeister\Competitions\Http\Controllers\Api\Competitions;

use Illuminate\Support\Arr;
use Motor\Backend\Http\Controllers\Controller;

use Partymeister\Competitions\Models\Competition;
use Partymeister\Competitions\Transformers\EntryTransformer;

class PlaylistsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Competition $competition)
    {

        $response = $this->checkIfCompetitionIsValid($competition);
        if ($response !== true) {
            return $response;
        }

        $resource = $this->transformCollection($competition->sorted_entries, EntryTransformer::class);

        $data = $this->fractal->createData($resource)->toArray();
        $data = Arr::get($data, 'data');

        $data = [
            'message' => 'Competition playlist read',
            'data'    => [
                'competition' => [
                    'name'         => $competition->name,
                    'is_anonymous' => (bool) $competition->competition_type->is_anonymous
                ],
                'entries'     => [ 'data' => $data ]
            ]
        ];

        return response()->json($data);
    }


    protected function checkIfCompetitionIsValid($competition)
    {
        // Check for entries with status 0 or 2 (unchecked and needs feedback)
        if ($competition->entries()->whereIn('status', [ 0, 2 ])->count() > 0) {
            return response()->json([
                'competition' => $competition->name,
                'message'     => 'Not all entries are checked and/or disqualified!'
            ], 422);
        }

        $sort_position = 1;
        foreach ($competition->entries()->where('status', 1)->orderBy('sort_position', 'ASC')->get() as $entry) {
            if ($entry->sort_position != $sort_position) {
                return response()->json([
                    'competition' => $competition->name,
                    'message'     => 'Not all entries are correctly numbered! Check the sort positions!'
                ], 422);
            }
            $sort_position++;
        }

        if ($competition->competition_type->has_composer && $competition->entries()
                                                                        ->where('status', 1)
                                                                        ->where('composer_not_member_of_copyright_collective',
                                                                            false)
                                                                        ->count() > 0) {
            if ($entry->sort_position != $sort_position) {
                return response()->json([
                    'competition' => $competition->name,
                    'message'     => 'Some entries have composers registered with a copyright collective!'
                ], 422);
            }
        }

        return true;
    }

}