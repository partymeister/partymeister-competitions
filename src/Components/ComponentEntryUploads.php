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
use Partymeister\Competitions\Forms\Component\EntryUploadForm;
use Partymeister\Competitions\Models\Component\ComponentEntryUpload;
use Partymeister\Competitions\Models\Entry;
use Partymeister\Competitions\Services\EntryService;
use Partymeister\Core\Services\StuhlService;

/**
 * Class ComponentEntryUploads
 *
 * @package Partymeister\Competitions\Components
 */
class ComponentEntryUploads
{
    use FormBuilderTrait;

    /**
     * @var ComponentEntryUpload
     */
    protected $component;

    /**
     * @var PageVersionComponent
     */
    protected $pageVersionComponent;

    /**
     * @var
     */
    protected $entryUploadForm;

    /**
     * @var
     */
    protected $record;

    /**
     * @var
     */
    protected $request;

    /**
     * ComponentEntryUploads constructor.
     *
     * @param PageVersionComponent $pageVersionComponent
     * @param ComponentEntryUpload $component
     */
    public function __construct(
        PageVersionComponent $pageVersionComponent,
        ComponentEntryUpload $component
    ) {
        $this->component = $component;
        $this->pageVersionComponent = $pageVersionComponent;
    }

    /**
     * @param Request $request
     * @return Factory|RedirectResponse|Redirector|View
     * @throws GuzzleException
     */
    public function index(Request $request)
    {
        $visitor = Auth::guard('visitor')
                       ->user();

        if (is_null($visitor)) {
            return redirect()->back();
        }

        $this->request = $request;

        $url = $this->request->url();

        // Check if we have ssl enabled and rewrite the url
        if (app()->environment('production')) {
            $url = str_replace('http:', 'https:', $url);
        }

        $formOptions = [
            'name'    => 'entry-upload',
            'url'     => $url,
            'method'  => 'POST',
            'enctype' => 'multipart/form-data',
        ];

        if (! is_null($request->get('entry_id'))) {

            $url = $this->request->url().'?entry_id='.$this->record->id;

            // Check if we have ssl enabled and rewrite the url
            if (app()->environment('production')) {
                $url = str_replace('http:', 'https:', $url);
            }

            $this->record = Entry::find($request->get('entry_id'));
            if (is_null($this->record) || $visitor->id != $this->record->visitor_id) {
                return redirect()->back();
            }
            $formOptions['url'] = $url;
            $formOptions['model'] = $this->record;
            $formOptions['method'] = 'PATCH';
        }

        $this->entryUploadForm = $this->form(EntryUploadForm::class, $formOptions);

        switch ($request->method()) {
            case 'POST':
                $result = $this->post();
                if ($result instanceof RedirectResponse) {
                    return $result;
                }
                break;
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
     */
    protected function post()
    {
        // It will automatically use current request, get the rules, and do the validation
        if ((int) $this->request->input($this->entryUploadForm->getName().'.reload_on_change') == 1) {
            return redirect()
                ->back()
                ->withInput();
        }
        if (! $this->entryUploadForm->isValid()) {
            return redirect()
                ->back()
                ->withErrors($this->entryUploadForm->getErrors())
                ->withInput();
        }

        $record = EntryService::createWithForm($this->request, $this->entryUploadForm)
                              ->getResult();

        StuhlService::send($record->visitor->name.' just created the entry '.$record->title.' in the '.$record->competition->name.' competition!');

        return redirect(route('frontend.pages.index', ['slug' => $this->component->entries_page->full_slug]));
    }

    /**
     * @return RedirectResponse|Redirector
     * @throws GuzzleException
     */
    protected function patch()
    {
        if (! $this->record->competition->upload_enabled && ! $this->record->upload_enabled) {
            return redirect(route('frontend.pages.index', ['slug' => $this->component->entries_page->full_slug]));
        }

        $this->entryUploadForm->getField('file')
                              ->setOption('rules', '');

        // It will automatically use current request, get the rules, and do the validation
        if ((int) $this->request->input($this->entryUploadForm->getName().'.reload_on_change') == 1) {
            return redirect()
                ->back()
                ->withInput();
        }

        // It will automatically use current request, get the rules, and do the validation
        if (! $this->entryUploadForm->isValid()) {
            return redirect()
                ->back()
                ->withErrors($this->entryUploadForm->getErrors())
                ->withInput();
        }

        $record = EntryService::updateWithForm($this->record, $this->request, $this->entryUploadForm)
                              ->getResult();

        StuhlService::send($record->visitor->name.' just updated the entry '.$record->title.' in the '.$record->competition->name.' competition!');

        return redirect(route('frontend.pages.index', ['slug' => $this->component->entries_page->full_slug]));
    }

    /**
     * @return Factory|View
     */
    public function render()
    {
        return view(config('motor-cms-page-components.components.'.$this->pageVersionComponent->component_name.'.view'), [
            'entryUploadForm' => $this->entryUploadForm,
            'record'          => $this->record,
            'component'       => $this->component,
        ]);
    }
}
