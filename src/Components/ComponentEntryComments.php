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
use Partymeister\Competitions\Forms\Component\EntryCommentForm;
use Partymeister\Competitions\Models\Comment;
use Partymeister\Competitions\Models\Entry;
use Partymeister\Core\Services\StuhlService;

/**
 * Class ComponentEntryComments
 */
class ComponentEntryComments
{
    use FormBuilderTrait;

    /**
     * @var PageVersionComponent
     */
    protected $pageVersionComponent;

    protected $entryCommentForm;

    protected $request;

    protected $comments;

    protected $record;

    protected $visitor;

    /**
     * ComponentEntryComments constructor.
     */
    public function __construct(PageVersionComponent $pageVersionComponent)
    {
        $this->pageVersionComponent = $pageVersionComponent;
    }

    /**
     * @return Factory|RedirectResponse|Redirector|View
     *
     * @throws GuzzleException
     */
    public function index(Request $request)
    {
        $this->visitor = Auth::guard('visitor')
            ->user();

        if (is_null($this->visitor)) {
            return redirect()->back();
        }

        $this->request = $request;

        if (is_null($request->get('entry_id'))) {
            return redirect()->back();
        }

        $this->record = Entry::find($request->get('entry_id'));

        if (is_null($this->record) || $this->visitor->id != $this->record->visitor_id) {
            return redirect()->back();
        }

        $url = $this->request->url().'?entry_id='.$this->record->id;
        if (app()->environment('production')) {
            $url = str_replace('http:', 'https:', $url);
        }

        $this->entryCommentForm = $this->form(EntryCommentForm::class, [
            'name' => 'entry-comment',
            'method' => 'POST',
            'url' => $url,
            'enctype' => 'multipart/form-data',
            'model' => $this->record,
        ]);

        $this->comments = $this->record->comments;

        switch ($request->method()) {
            case 'POST':
                $result = $this->post();
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
    protected function post()
    {
        if ($this->request->input($this->entryCommentForm->getName().'.mark_as_read') == 1) {
            foreach ($this->record->comments()
                ->get() as $comment) {
                $comment->read_by_visitor = true;
                $comment->save();
            }

            return redirect($this->request->url().'?entry_id='.$this->record->id);
        } else {
            $this->entryCommentForm->getField('message')
                ->setOption('rules', ['required']);
        }

        if (! $this->entryCommentForm->isValid()) {
            return redirect()
                ->back()
                ->withErrors($this->entryCommentForm->getErrors())
                ->withInput();
        }

        foreach ($this->record->comments()
            ->get() as $comment) {
            $comment->read_by_visitor = true;
            $comment->save();
        }

        $c = new Comment;
        $c->visitor_id = $this->visitor->id;
        $c->read_by_visitor = true;
        $c->model_type = get_class($this->record);
        $c->model_id = $this->record->id;
        $c->message = $this->request->input($this->entryCommentForm->getName().'.message');
        $c->save();

        StuhlService::send($this->visitor->name.' just wrote a comment for the entry '.$this->record->name.' in the '.$this->record->competition->name.' competition!');

        return redirect($this->request->url().'?entry_id='.$this->record->id);
    }

    /**
     * @return Factory|View
     */
    public function render()
    {
        return view(config('motor-cms-page-components.components.'.$this->pageVersionComponent->component_name.'.view'), [
            'comments' => $this->comments,
            'entryCommentForm' => $this->entryCommentForm,
            'record' => $this->record,
        ]);
    }
}
