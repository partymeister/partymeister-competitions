<?php

namespace Partymeister\Competitions\Http\Controllers\Backend;

use Illuminate\Http\Response;
use Kris\LaravelFormBuilder\FormBuilderTrait;
use Motor\Backend\Http\Controllers\Controller;
use Partymeister\Competitions\Forms\Backend\VoteForm;
use Partymeister\Competitions\Http\Requests\Backend\ManualVoteRequest;
use Partymeister\Competitions\Http\Requests\Backend\VoteRequest;
use Partymeister\Competitions\Models\Vote;
use Partymeister\Competitions\Services\VoteService;

/**
 * Class VotesController
 */
class VotesController extends Controller
{
    use FormBuilderTrait;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $results = VoteService::getAllVotesByRank();
        $special = VoteService::getAllSpecialVotesByRank();
        unset($special['entries']);

        $deadlineTimestamp = strtotime(config('partymeister-competitions-voting.deadline'));
        $deadlineOver = ($deadlineTimestamp < time() ? true : false);

        return view('partymeister-competitions::backend.votes.index', compact('results', 'special', 'deadlineOver'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $form = $this->form(VoteForm::class, [
            'method'  => 'POST',
            'route'   => 'backend.votes.store',
            'enctype' => 'multipart/form-data',
        ]);

        $results = VoteService::getAllVotesByRank();

        return view('partymeister-competitions::backend.votes.create', compact('form', 'results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  VoteRequest  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(ManualVoteRequest $request)
    {
        $form = $this->form(VoteForm::class);

        // It will automatically use current request, get the rules, and do the validation
        if (! $form->isValid()) {
            return redirect()
                ->back()
                ->withErrors($form->getErrors())
                ->withInput();
        }

        VoteService::addVotes($request);

        flash()->success(trans('partymeister-competitions::backend/votes.created'));

        return redirect('backend/votes');
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Vote  $record
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Vote $record)
    {
        $form = $this->form(VoteForm::class, [
            'method'  => 'PATCH',
            'url'     => route('backend.votes.update', [$record->id]),
            'enctype' => 'multipart/form-data',
            'model'   => $record,
        ]);

        return view('partymeister-competitions::backend.votes.edit', compact('form'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  VoteRequest  $request
     * @param  Vote  $record
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(VoteRequest $request, Vote $record)
    {
        $form = $this->form(VoteForm::class);

        // It will automatically use current request, get the rules, and do the validation
        if (! $form->isValid()) {
            return redirect()
                ->back()
                ->withErrors($form->getErrors())
                ->withInput();
        }

        VoteService::updateWithForm($record, $request, $form);

        flash()->success(trans('partymeister-competitions::backend/votes.updated'));

        return redirect('backend/votes');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Vote  $record
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy(Vote $record)
    {
        VoteService::delete($record);

        flash()->success(trans('partymeister-competitions::backend/votes.deleted'));

        return redirect('backend/votes');
    }
}
