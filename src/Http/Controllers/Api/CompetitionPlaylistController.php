<?php

namespace Partymeister\Competitions\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Motor\Admin\Helpers\MediaHelper;
use Motor\Admin\Http\Controllers\Controller;
use Partymeister\Competitions\Http\Resources\EntryResource;
use Partymeister\Competitions\Models\Competition;
use Partymeister\Slides\Models\SlideTemplate;

class CompetitionPlaylistController extends Controller
{
    public function show(Competition $competition): JsonResponse
    {
        $warnings = $this->validateCompetition($competition);
        if (!empty($warnings)) {
            return response()->json([
                'warnings' => $warnings,
            ]);
        }

        $entryCollection = EntryResource::collection($competition->qualified_entries->load('competition'));
        $entries = $entryCollection->toArrayRecursive();

        foreach ($entries as $key => $entry) {
            if ($entries[$key]['filesize'] == 0) {
                $entries[$key]['filesize_human'] = ' ';
            }
            if ($entries[$key]['description'] == '') {
                $entries[$key]['description'] = ' ';
            }
            $entries[$key]['description'] = nl2br($entries[$key]['description']);

            if ($key > 0) {
                $entries[$key]['previous_sort_position'] = (strlen($key) == 1 ? '0'.$key : $key);
                $entries[$key]['previous_author'] = $entries[$key - 1]['author'];
                $entries[$key]['previous_title'] = $entries[$key - 1]['title'];
            } else {
                $entries[$key]['previous_sort_position'] = ' ';
                $entries[$key]['previous_author'] = ' ';
                $entries[$key]['previous_title'] = ' ';
            }

            $entries[$key]['options_string'] = '';
            foreach (Arr::get($entry, 'options', []) as $i => $option) {
                $entries[$key]['options_string'] .= $option['name'].'<br>';
                $entries[$key]['option_'.($i + 1)] = $option['name'].'<br>';
            }
            $customOption = Arr::get($entry, 'custom_option');
            $entries[$key]['custom_option'] = $customOption;
            if ($customOption) {
                $entries[$key]['options_string'] .= $customOption.'<br>';
            }
        }

        $participants = [];
        if ($competition->competition_type->is_anonymous) {
            foreach ($entries as $key => $entry) {
                $participants[] = $entry['author'];
                $entries[$key]['author'] = ' ';
                $entries[$key]['previous_author'] = ' ';
            }
        }
        shuffle($participants);

        $templates = [];
        $templateTypes = [
            'coming_up', 'now', 'end', 'competition',
            'competition_entry_1', 'participants',
        ];
        foreach ($templateTypes as $type) {
            $template = SlideTemplate::where('template_for', $type)->first();
            if ($template) {
                $templates[$type] = [
                    'id' => $template->id,
                    'definitions' => $template->definitions,
                ];
            }
        }

        $videos = [];
        foreach ($competition->file_associations as $fileAssociation) {
            $videos[] = [
                'file_id' => $fileAssociation->file->id,
                'preview' => MediaHelper::getFileInformation($fileAssociation->file, 'file', false, ['preview', 'thumb'])['preview'] ?? '',
                'data' => MediaHelper::getFileInformation($fileAssociation->file, 'file', false, ['preview', 'thumb']),
            ];
        }

        return response()->json([
            'competition' => [
                'id' => $competition->id,
                'name' => $competition->name,
                'competition_type' => [
                    'is_anonymous' => (bool) $competition->competition_type->is_anonymous,
                ],
            ],
            'templates' => $templates,
            'entries' => $entries,
            'participants' => $participants,
            'videos' => $videos,
        ]);
    }

    public function store(Competition $competition, Request $request): JsonResponse
    {
        $data = [
            'slide' => [],
            'type' => [],
            'name' => [],
            'cached_html_preview' => [],
            'cached_html_final' => [],
            'id' => [],
        ];

        foreach ($request->input('slides', []) as $slide) {
            $key = $slide['key'];
            $data['slide'][$key] = $slide['definitions'];
            $data['type'][$key] = $slide['type'];
            $data['name'][$key] = $slide['name'];
            $data['cached_html_preview'][$key] = $slide['cached_html_preview'] ?? '';
            $data['cached_html_final'][$key] = $slide['cached_html_final'] ?? '';
            if (isset($slide['id'])) {
                $data['id'][$key] = $slide['id'];
            }
        }

        foreach ($request->input('videos', []) as $video) {
            $key = $video['key'];
            $data['slide'][$key] = json_encode($video);
            $data['type'][$key] = $key;
            $data['name'][$key] = ucfirst(str_replace('_', ' ', $key));
        }

        \Partymeister\Slides\Services\PlaylistService::generateCompetitionPlaylist($competition, $data);

        return response()->json(['status' => 'ok']);
    }

    protected function validateCompetition(Competition $competition): array
    {
        $warnings = [];

        // Check for entries with status 0 (unchecked) or 2 (needs feedback)
        if ($competition->entries()->whereIn('status', [0, 2])->count() > 0) {
            $warnings[] = 'Not all entries are checked and/or disqualified!';
        }

        // Check that qualified entries have sequential sort positions
        $sortPosition = 1;
        foreach ($competition->entries()->where('status', 1)->orderBy('sort_position', 'ASC')->get() as $entry) {
            if ($entry->sort_position != $sortPosition) {
                $warnings[] = 'Not all entries are correctly numbered! Check the sort positions!';
                break;
            }
            $sortPosition++;
        }

        // Check for copyright collective issues
        if ($competition->competition_type->has_composer
            && $competition->entries()->where('status', 1)->where('composer_not_member_of_copyright_collective', false)->count() > 0) {
            $warnings[] = 'Some entries have composers registered with a copyright collective!';
        }

        return $warnings;
    }
}
