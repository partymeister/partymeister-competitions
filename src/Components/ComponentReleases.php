<?php

namespace Partymeister\Competitions\Components;

use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Motor\CMS\Models\PageVersionComponent;
use Partymeister\Competitions\Models\Competition;

/**
 * Class ComponentReleases
 */
class ComponentReleases
{
    /**
     * @var PageVersionComponent
     */
    protected $pageVersionComponent;

    /**
     * @var
     */
    protected $competition;

    protected $entries;

    /**
     * ComponentReleases constructor.
     *
     * @param  PageVersionComponent  $pageVersionComponent
     */
    public function __construct(PageVersionComponent $pageVersionComponent)
    {
        $this->pageVersionComponent = $pageVersionComponent;
    }

    /**
     * @param  Request  $request
     * @return Factory|View
     */
    public function index(Request $request)
    {
        $query = Competition::where('voting_enabled', true)
                            ->orderBy('updated_at', 'ASC');

        if ($request->get('competition_id') > 0) {
            $query->where('id', $request->get('competition_id'));
        }

        $this->competition = $query->first();

        if (! is_null($this->competition)) {
            \View::share('activeCompetitionId', $this->competition->id);

            $this->entries = $this->competition->entries()
                ->where('status', 1)
                ->orderBy('sort_position', 'ASC')
                ->with(['competition.competition_type', 'media'])
                ->get();
        }

        return $this->render();
    }

    /**
     * @return Factory|View
     */
    public function render()
    {
        return view(config('motor-cms-page-components.components.'.$this->pageVersionComponent->component_name.'.view'), [
            'competition' => $this->competition,
            'entries'     => $this->entries ?? collect(),
        ]);
    }
}
