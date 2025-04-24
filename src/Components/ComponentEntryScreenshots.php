<?php

namespace Partymeister\Competitions\Components;

use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Kris\LaravelFormBuilder\FormBuilderTrait;
use Motor\CMS\Models\PageVersionComponent;
use Partymeister\Competitions\Forms\Component\EntryScreenshotForm;
use Partymeister\Competitions\Models\Component\ComponentEntryScreenshot;
use Partymeister\Competitions\Models\Entry;
use Partymeister\Competitions\Services\EntryService;
use Partymeister\Core\Services\StuhlService;

/**
 * Class ComponentEntryScreenshots
 */
class ComponentEntryScreenshots
{
    use FormBuilderTrait;

    /**
     * @var PageVersionComponent
     */
    protected $pageVersionComponent;

    /**
     * @var ComponentEntryScreenshot
     */
    protected $component;

    protected $entryScreenshotForm;

    protected $record;

    protected $request;

    /**
     * ComponentEntryScreenshots constructor.
     */
    public function __construct(
        PageVersionComponent $pageVersionComponent,
        ComponentEntryScreenshot $component
    ) {
        $this->pageVersionComponent = $pageVersionComponent;
        $this->component = $component;
    }

    /**
     * @return Factory|RedirectResponse|Redirector|View
     *
     * @throws GuzzleException
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

        $this->record = Entry::find($request->get('entry_id'));
        if (is_null($this->record)) {
            return redirect()->back();
        }

        if ($visitor->id != $this->record->visitor_id) {
            return redirect()->back();
        }

        $this->request = $request;

        $url = $this->request->url().'?entry_id='.$this->record->id;

        // Check if we have ssl enabled and rewrite the url
        if (app()->environment('production')) {
            $url = str_replace('http:', 'https:', $url);
        }

        $this->entryScreenshotForm = $this->form(EntryScreenshotForm::class, [
            'name' => 'entry-screenshot',
            'url' => $url,
            'method' => 'PATCH',
            'enctype' => 'multipart/form-data',
            'model' => $this->record,
        ]);

        switch ($request->method()) {
            case 'PATCH':
                $result = $this->patch();
                if ($result instanceof RedirectResponse) {
                    return $result;
                }
                break;
        }

        return $this->render();
    }

    /**
     * @return RedirectResponse|Redirector
     *
     * @throws GuzzleException
     */
    protected function patch()
    {
        // It will automatically use current request, get the rules, and do the validation
        if (! $this->entryScreenshotForm->isValid()) {
            return redirect()
                ->back()
                ->withErrors($this->entryScreenshotForm->getErrors())
                ->withInput();
        }

        $record = EntryService::updateWithForm($this->record, $this->request, $this->entryScreenshotForm)
            ->getResult();

        StuhlService::send($record->visitor->name.' just updated the screenshot for the entry '.$record->title.' in the '.$record->competition->name.' competition!');

        return redirect(route('frontend.pages.index', ['slug' => $this->component->entries_page->full_slug]));
    }

    /**
     * @return Factory|View
     */
    public function render()
    {
        return view(config('motor-cms-page-components.components.'.$this->pageVersionComponent->component_name.'.view'), [
            'entryScreenshotForm' => $this->entryScreenshotForm,
            'record' => $this->record,
            'component' => $this->component,
        ]);
    }
}
