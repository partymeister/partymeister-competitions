<?php

namespace Partymeister\Competitions\Http\Controllers\Backend\Component;

use Illuminate\Http\Request;
use Kris\LaravelFormBuilder\FormBuilderTrait;
use Motor\CMS\Http\Controllers\Component\ComponentController;
use Partymeister\Competitions\Forms\Backend\Component\ComponentVotingForm;
use Partymeister\Competitions\Models\Component\ComponentVoting;
use Partymeister\Competitions\Services\Component\ComponentVotingService;

/**
 * Class ComponentVotingsController
 */
class ComponentVotingsController extends ComponentController
{
    use FormBuilderTrait;

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create()
    {
        $this->form = $this->form(ComponentVotingForm::class);

        return response()->json($this->getFormData('component.votings.store', ['mediapool' => false]));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $this->form = $this->form(ComponentVotingForm::class);

        if (! $this->isValid()) {
            return $this->respondWithValidationError();
        }

        ComponentVotingService::createWithForm($request, $this->form);

        return response()->json(['message' => trans('partymeister-competitions::component/votings.created')]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  ComponentVoting  $record
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(ComponentVoting $record)
    {
        $this->form = $this->form(ComponentVotingForm::class, [
            'model' => $record,
        ]);

        return response()->json($this->getFormData('component.votings.update', ['mediapool' => false]));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  ComponentVoting  $record
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, ComponentVoting $record)
    {
        $form = $this->form(ComponentVotingForm::class);

        if (! $this->isValid()) {
            return $this->respondWithValidationError();
        }

        ComponentVotingService::updateWithForm($record, $request, $form);

        return response()->json(['message' => trans('partymeister-competitions::component/votings.updated')]);
    }
}
