<?php

namespace Partymeister\Competitions\Http\Controllers\Backend;

use Illuminate\Http\Response;
use Kris\LaravelFormBuilder\FormBuilderTrait;
use Motor\Backend\Http\Controllers\Controller;
use Partymeister\Competitions\Forms\Backend\CompetitionPrizeForm;
use Partymeister\Competitions\Grids\CompetitionPrizeGrid;
use Partymeister\Competitions\Http\Requests\Backend\CompetitionPrizeRequest;
use Partymeister\Competitions\Models\Competition;
use Partymeister\Competitions\Models\CompetitionPrize;
use Partymeister\Competitions\Services\CompetitionPrizeService;

/**
 * Class CompetitionPrizesController
 *
 * @package Partymeister\Competitions\Http\Controllers\Backend
 */
class CompetitionPrizesController extends Controller
{
    use FormBuilderTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \ReflectionException
     */
    public function index()
    {
        $grid = new CompetitionPrizeGrid(CompetitionPrize::class);

        $service = CompetitionPrizeService::collection($grid);
        $grid->setFilter($service->getFilter());
        $paginator = $service->getPaginator();

        return view('partymeister-competitions::backend.competition_prizes.index', compact('paginator', 'grid'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $form = $this->form(CompetitionPrizeForm::class, [
            'method'  => 'POST',
            'route'   => 'backend.competition_prizes.store',
            'enctype' => 'multipart/form-data',
        ]);

        $competitions = Competition::where('has_prizegiving', true)
                                   ->orderBy('prizegiving_sort_position', 'ASC')
                                   ->get();

        return view('partymeister-competitions::backend.competition_prizes.create', compact('form', 'competitions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CompetitionPrizeRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(CompetitionPrizeRequest $request)
    {
        $form = $this->form(CompetitionPrizeForm::class);

        // It will automatically use current request, get the rules, and do the validation
        if (! $form->isValid()) {
            return redirect()
                ->back()
                ->withErrors($form->getErrors())
                ->withInput();
        }

        CompetitionPrizeService::createOrUpdatePrizes($request);

        flash()->success(trans('partymeister-competitions::backend/competition_prizes.created'));

        return redirect('backend/competition_prizes/create');
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
     * @param CompetitionPrize $record
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(CompetitionPrize $record)
    {
        $form = $this->form(CompetitionPrizeForm::class, [
            'method'  => 'PATCH',
            'url'     => route('backend.competition_prizes.update', [$record->id]),
            'enctype' => 'multipart/form-data',
            'model'   => $record,
        ]);

        return view('partymeister-competitions::backend.competition_prizes.edit', compact('form'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CompetitionPrizeRequest $request
     * @param CompetitionPrize $record
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(CompetitionPrizeRequest $request, CompetitionPrize $record)
    {
        $form = $this->form(CompetitionPrizeForm::class);

        // It will automatically use current request, get the rules, and do the validation
        if (! $form->isValid()) {
            return redirect()
                ->back()
                ->withErrors($form->getErrors())
                ->withInput();
        }

        CompetitionPrizeService::updateWithForm($record, $request, $form);

        flash()->success(trans('partymeister-competitions::backend/competition_prizes.updated'));

        return redirect('backend/competition_prizes');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param CompetitionPrize $record
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy(CompetitionPrize $record)
    {
        CompetitionPrizeService::delete($record);

        flash()->success(trans('partymeister-competitions::backend/competition_prizes.deleted'));

        return redirect('backend/competition_prizes');
    }
}
