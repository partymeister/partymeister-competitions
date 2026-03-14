<?php

namespace Partymeister\Competitions\Components;

use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Motor\CMS\Models\PageVersionComponent;
use Partymeister\Competitions\Http\Resources\EntryResource;
use Partymeister\Competitions\Models\Entry;
use Partymeister\Slides\Models\SlideTemplate;

/**
 * Class ComponentEntryDetails
 */
class ComponentEntryDetails
{
    /**
     * @var PageVersionComponent
     */
    protected $pageVersionComponent;

    /**
     * @var array
     */
    protected $data = [];

    /**
     * ComponentEntryDetails constructor.
     *
     * @param  PageVersionComponent  $pageVersionComponent
     */
    public function __construct(PageVersionComponent $pageVersionComponent)
    {
        $this->pageVersionComponent = $pageVersionComponent;
    }

    /**
     * @param  Request  $request
     * @return Factory|RedirectResponse|View
     */
    public function index(Request $request)
    {
        $visitor = Auth::guard('visitor')
                       ->user();

        if (is_null($visitor)) {
            return redirect()->back();
        }

        if (is_null($request->get('entry_id'))) {
            return redirect()->back();
        }

        $record = Entry::find($request->get('entry_id'));
        if (is_null($record)) {
            return redirect()->back();
        }

        if ($visitor->id != $record->visitor_id) {
            return redirect()->back();
        }

        // Build entry data exactly like CompetitionPlaylistController::show()
        $entry = (new EntryResource($record->load('competition')))->toArrayRecursive();

        if ($entry['filesize'] == 0) {
            $entry['filesize_human'] = ' ';
        }
        if ($entry['description'] == '') {
            $entry['description'] = ' ';
        }
        $entry['description'] = nl2br($entry['description']);

        // Preview-specific: no previous entry, fake position
        $entry['sort_position_prefixed'] = '99';
        $entry['previous_sort_position'] = ' ';
        $entry['previous_author'] = ' ';
        $entry['previous_title'] = ' ';

        // Options — same logic as CompetitionPlaylistController
        // toArrayRecursive may wrap in {data:[...]} or return flat array
        $entry['options_string'] = '';
        $options = Arr::get($entry, 'options.data', Arr::get($entry, 'options', []));
        foreach ($options as $i => $option) {
            $name = is_array($option) ? ($option['name'] ?? '') : (string) $option;
            if ($name) {
                $entry['options_string'] .= $name.'<br>';
                $entry['option_'.($i + 1)] = $name.'<br>';
            }
        }
        $customOption = (string) Arr::get($entry, 'custom_option', '');
        $entry['custom_option'] = $customOption;
        if ($customOption) {
            $entry['options_string'] .= $customOption.'<br>';
        }

        // Ensure all placeholder fields are strings
        foreach (['platform', 'ai_data', 'engine_data', 'remote_type', 'running_time'] as $field) {
            $entry[$field] = (string) ($entry[$field] ?? '');
        }
        $entry['competition_name'] = Arr::get($entry, 'competition.name', '');

        $competitionTemplate = SlideTemplate::where('template_for', 'competition')
                                            ->first();

        // Create a temp slide with entry data replaced in definitions for live preview
        $beamslideUrl = null;
        if ($competitionTemplate) {
            $defsArray = json_decode($competitionTemplate->definitions, true);
            // Replace placeholders: mirrors renderCompetitionEntry() from useSlideReplacer.ts
            // For each element, replace <<key>> in placeholder field, write result to content
            if (isset($defsArray['elements'])) {
                foreach ($defsArray['elements'] as &$element) {
                    if (!isset($element['properties'])) continue;
                    $props = &$element['properties'];

                    $source = $props['placeholder'] ?? '';
                    if (trim($source) === '') continue;

                    // Replace all entry properties as placeholders (same as JS renderCompetitionEntry)
                    foreach ($entry as $key => $value) {
                        if (is_array($value) || is_object($value)) continue;
                        $strValue = (string) ($value ?? '');
                        if ($key === 'remote_type') {
                            $strValue = strtolower($strValue);
                        }
                        $source = str_replace('<<'.$key.'>>', $strValue, $source);
                    }
                    // Strip remaining placeholders
                    $source = preg_replace('/<<[^>]+>>/', '', $source);
                    // Remove \r (sanitizeContent equivalent)
                    $source = str_replace("\r", '', $source);
                    $props['content'] = $source;
                }
                unset($element);
            }
            $definitions = json_encode($defsArray);

            // Store in cache with a stable key per entry (no DB record needed)
            $cacheKey = 'entry_'.$record->id.'_'.md5($definitions);
            cache()->put('slide_preview:'.$cacheKey, $definitions, now()->addHour());

            $beamslideUrl = route('backend.slides.render-preview', ['cacheKey' => $cacheKey]);
        }

        $this->data = [
            'entry'               => $entry,
            'record'              => $record,
            'competitionTemplate' => $competitionTemplate,
            'beamslideUrl'        => $beamslideUrl,
        ];

        return $this->render();
    }

    /**
     * @return Factory|View
     */
    public function render()
    {
        return view(config('motor-cms-page-components.components.'.$this->pageVersionComponent->component_name.'.view'), $this->data);
    }
}
