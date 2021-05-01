<?php

namespace Partymeister\Competitions\Components;

use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Motor\CMS\Models\PageVersionComponent;
use Partymeister\Competitions\Models\Component\ComponentEntry;

/**
 * Class ComponentEntries
 *
 * @package Partymeister\Competitions\Components
 */
class ComponentEntries
{
    /**
     * @var ComponentEntry
     */
    protected $component;

    /**
     * @var PageVersionComponent
     */
    protected $pageVersionComponent;

    /**
     * @var
     */
    protected $visitor;

    /**
     * ComponentEntries constructor.
     *
     * @param PageVersionComponent $pageVersionComponent
     * @param ComponentEntry $component
     */
    public function __construct(
        PageVersionComponent $pageVersionComponent,
        ComponentEntry $component
    ) {
        $this->component = $component;
        $this->pageVersionComponent = $pageVersionComponent;
    }

    /**
     * @param Request $request
     * @return Factory|RedirectResponse|Redirector|View
     */
    public function index(Request $request)
    {
        $this->visitor = Auth::guard('visitor')
                             ->user();

        return $this->render();
    }

    /**
     * @return Factory|RedirectResponse|Redirector|View
     */
    public function render()
    {
        if (! is_null($this->visitor)) {
            return view(config('motor-cms-page-components.components.'.$this->pageVersionComponent->component_name.'.view'), [
                    'component' => $this->component,
                    'entries'   => $this->visitor->entries,
                ]);
        } else {
            return redirect('/start');
        }
    }
}
