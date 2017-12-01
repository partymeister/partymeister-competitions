<?php

namespace Partymeister\Competitions\Http\Controllers\Backend\Competitions;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use League\Fractal\Resource\ResourceAbstract;
use Motor\Backend\Http\Controllers\Controller;

use Partymeister\Competitions\Models\Competition;

use Kris\LaravelFormBuilder\FormBuilderTrait;
use Partymeister\Competitions\Transformers\Competition\EntryTransformer;

class PlaylistsController extends Controller
{

    use FormBuilderTrait;


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Competition $competition, Request $request)
    {
        $filename = Str::slug($competition->name . '_' . date('Y-m-d_H-i-s'));
        switch ($request->get('format', 'json')) {
            case 'json':
                $resource = $this->transformCollection($competition->sorted_entries, EntryTransformer::class);

                $data = $this->fractal->createData($resource)->toArray();
                $data = Arr::get($data, 'data');

                $data = [
                    'message' => 'Competition playlist for \'' . $competition->name . '\', generated ' . date('Y-m-d H:i:s'),
                    'data'    => [
                        'competition' => [ 'name'         => $competition->name,
                                           'is_anonymous' => (bool) $competition->competition_type->is_anonymous
                        ],
                        'entries'     => [ 'data' => $data ]
                    ]
                ];

                if ($request->get('download')) {
                    return response()->attachment(json_encode($data), $filename, 'application/json');
                }

                return response()->json($data);
            case 'm3u':
                $m3u = $this->generateM3u($competition->sorted_entries);

                if ($request->get('download')) {
                    return response()->attachment($m3u, $filename . '.m3u', 'audio/x-mpegurl');
                }

                return $m3u;
                break;
        }
    }


    protected function generateM3u($entries)
    {
        $output = '';

        foreach ($entries as $entry) {
            if ($entry->competition->competition_type->is_anonymous) {
                $output .= "#EXTINF:-1," . str_replace(' - ', '-', $entry->title) . "\r\n";
            } else {
                $output .= "#EXTINF:-1," . str_replace(' - ', '-', $entry->author) . " - " . str_replace(' - ', '-',
                        $entry->title) . "\r\n";
            }
        }

        return $output;
    }
}
