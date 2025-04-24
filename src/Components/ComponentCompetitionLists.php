<?php

namespace Partymeister\Competitions\Components;

use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Motor\CMS\Models\PageVersionComponent;
use Partymeister\Competitions\Models\Competition;

/**
 * Class ComponentCompetitionLists
 */
class ComponentCompetitionLists
{
    /**
     * @var PageVersionComponent
     */
    protected $pageVersionComponent;

    /**
     * ComponentCompetitionLists constructor.
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
        return $this->render();
    }

    /**
     * @return Factory|View
     */
    public function render()
    {
        $competitions = Competition::where('voting_enabled', true)
            ->orderBy('updated_at', 'ASC')
            ->get();

        return view(config('motor-cms-page-components.components.'.$this->pageVersionComponent->component_name.'.view'), ['competitions' => $competitions]);
    }
}
