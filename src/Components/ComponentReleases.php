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

    protected $competition;

    /**
     * ComponentReleases constructor.
     */
    public function __construct(PageVersionComponent $pageVersionComponent)
    {
        $this->pageVersionComponent = $pageVersionComponent;
    }

    /**
     * @return Factory|View
     */
    public function index(Request $request)
    {
        if ($request->get('competition_id') > 0) {
            $this->competition = Competition::where('voting_enabled', true)
                ->where('id', $request->get('competition_id'))
                ->orderBy('updated_at', 'ASC')
                ->first();
        } else {
            $this->competition = Competition::where('voting_enabled', true)
                ->orderBy('updated_at', 'ASC')
                ->first();
        }

        if (! is_null($this->competition)) {
            \View::share('activeCompetitionId', $this->competition->id);
        }

        return $this->render();
    }

    /**
     * @return Factory|View
     */
    public function render()
    {
        return view(config('motor-cms-page-components.components.'.$this->pageVersionComponent->component_name.'.view'), ['competition' => $this->competition]);
    }
}
