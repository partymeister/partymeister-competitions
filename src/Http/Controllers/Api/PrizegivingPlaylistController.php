<?php

namespace Partymeister\Competitions\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Motor\Backend\Http\Controllers\Controller;
use Partymeister\Competitions\Services\VoteService;
use Partymeister\Slides\Models\SlideTemplate;
use Partymeister\Slides\Services\PlaylistService;

class PrizegivingPlaylistController extends Controller
{
    public function show(): JsonResponse
    {
        $results = VoteService::getAllVotesByRank('ASC');
        $specialVotes = VoteService::getAllSpecialVotesByRank();

        if (isset($specialVotes['entries'])) {
            unset($specialVotes['entries']);
        }

        $maxEntries = config('partymeister-slides-prizegiving.entries', 6);

        // Trim and shuffle special votes
        foreach ($specialVotes as $entryKey => $entry) {
            if ($entryKey > $maxEntries - 1) {
                unset($specialVotes[$entryKey]);
            }
        }
        shuffle($specialVotes);

        // Process comments per competition
        $comments = [];
        foreach ($results as $competition) {
            $compComments = [];
            foreach ($competition['entries'] as $entry) {
                if (! isset($entry['comments'])) {
                    continue;
                }
                foreach ($entry['comments'] as $comment) {
                    if (strlen($comment) < 30) {
                        $compComments[] = $comment;
                        $compComments = array_unique($compComments);
                    }
                }
            }
            shuffle($compComments);
            $chunks = array_chunk($compComments, 8);
            $compComments = count($chunks) > 0 ? $chunks[0] : [];
            $comments[$competition['id']] = implode('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', $compComments);
        }

        // Trim and shuffle entries per competition
        foreach ($results as $key => $competition) {
            foreach ($competition['entries'] as $entryKey => $entry) {
                if ($entryKey > $maxEntries - 1) {
                    unset($results[$key]['entries'][$entryKey]);
                }
            }
            shuffle($results[$key]['entries']);
        }

        // Load templates
        $templateTypes = ['prizegiving', 'coming_up', 'now', 'end_of_pg', 'comments'];
        $templates = [];
        foreach ($templateTypes as $type) {
            $template = SlideTemplate::where('template_for', $type)->first();
            if ($template) {
                $templates[$type] = [
                    'id' => $template->id,
                    'definitions' => $template->definitions,
                ];
            }
        }

        return response()->json([
            'results' => $results,
            'specialVotes' => array_values($specialVotes),
            'comments' => $comments,
            'templates' => $templates,
            'config' => [
                'entries' => $maxEntries,
                'bar_color' => config('partymeister-slides-prizegiving.bar_color', '#FFFFFF'),
            ],
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = [
            'slide' => [],
            'type' => [],
            'name' => [],
            'meta' => [],
            'cached_html_preview' => [],
            'cached_html_final' => [],
        ];

        foreach ($request->input('slides', []) as $slide) {
            $key = $slide['key'];
            $data['slide'][$key] = $slide['definitions'];
            $data['type'][$key] = $slide['type'];
            $data['name'][$key] = $slide['name'];
            $data['cached_html_preview'][$key] = $slide['cached_html_preview'] ?? '';
            $data['cached_html_final'][$key] = $slide['cached_html_final'] ?? '';
            if (isset($slide['meta'])) {
                $data['meta'][$key] = $slide['meta'];
            }
        }

        PlaylistService::generatePrizegivingPlaylist($data);

        return response()->json(['status' => 'ok']);
    }
}
