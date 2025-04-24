<?php

namespace Partymeister\Competitions\Components;

use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Motor\CMS\Models\PageVersionComponent;

/**
 * Class ComponentLiveVotings
 */
class ComponentLiveVotings
{
    /**
     * @var PageVersionComponent
     */
    protected $pageVersionComponent;

    protected $visitor;

    /**
     * ComponentLiveVotings constructor.
     */
    public function __construct(PageVersionComponent $pageVersionComponent)
    {
        $this->pageVersionComponent = $pageVersionComponent;
    }

    /**
     * @return Factory|RedirectResponse|Redirector|View
     */
    public function index(Request $request)
    {
        $this->visitor = Auth::guard('visitor')
            ->user();
        if (is_null($this->visitor)) {
            return redirect(route('frontend.pages.index', ['slug' => 'start']));
        }

        return $this->render();
    }

    /**
     * @return Factory|View
     */
    public function render()
    {
        return view(config('motor-cms-page-components.components.'.$this->pageVersionComponent->component_name.'.view'), []);
    }
}
