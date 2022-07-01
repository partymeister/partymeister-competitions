<?php

namespace Partymeister\Competitions\Components;

use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
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

        $entry = (new EntryResource($record->load('competition')))->toArrayRecursive();

        foreach (Arr::get($entry, 'options.data', []) as $i => $option) {
            $entry['option_'.($i + 1)] = $option['name'];
        }

        $entry['competition_name'] = strtoupper($entry['competition']['name']);
        $entry['filesize_bytes'] = $entry['filesize'];
        if ($entry['filesize_bytes'] == 0) {
            $entry['filesize_human'] = ' ';
        }
        if ($entry['description'] == '') {
            $entry['description'] = ' ';
        }
        $entry['description'] = nl2br($entry['description']);
        $entry['sort_position_prefixed'] = rand(10, 99);
        $entry['previous_sort_position'] = ' ';
        $entry['previous_author'] = ' ';
        $entry['previous_title'] = ' ';

        $competitionTemplate = SlideTemplate::where('template_for', 'competition')
                                            ->first();

        $this->data = [
            'entry'               => $entry,
            'record'              => $record,
            'competitionTemplate' => $competitionTemplate,
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
