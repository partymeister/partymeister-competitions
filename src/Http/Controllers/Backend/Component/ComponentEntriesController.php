<?php

namespace Partymeister\Competitions\Http\Controllers\Backend\Component;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Kris\LaravelFormBuilder\FormBuilderTrait;
use Motor\CMS\Http\Controllers\Component\ComponentController;
use Partymeister\Competitions\Forms\Backend\Component\ComponentEntryForm;
use Partymeister\Competitions\Models\Component\ComponentEntry;
use Partymeister\Competitions\Services\Component\ComponentEntryService;

/**
 * Class ComponentEntriesController
 *
 * @package Partymeister\Competitions\Http\Controllers\Backend\Component
 */
class ComponentEntriesController extends ComponentController
{
    use FormBuilderTrait;

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $this->form = $this->form(ComponentEntryForm::class);

        return response()->json($this->getFormData('component.entries.store', ['mediapool' => false]));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $this->form = $this->form(ComponentEntryForm::class);

        if (! $this->isValid()) {
            return $this->respondWithValidationError();
        }

        ComponentEntryService::createWithForm($request, $this->form);

        return response()->json(['message' => trans('partymeister-competitions::component/entries.created')]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param ComponentEntry $record
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(ComponentEntry $record)
    {
        $this->form = $this->form(ComponentEntryForm::class, [
            'model' => $record,
        ]);

        return response()->json($this->getFormData('component.entries.update', ['mediapool' => false]));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param ComponentEntry $record
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, ComponentEntry $record)
    {
        $form = $this->form(ComponentEntryForm::class);

        if (! $this->isValid()) {
            return $this->respondWithValidationError();
        }

        ComponentEntryService::updateWithForm($record, $request, $form);

        return response()->json(['message' => trans('partymeister-competitions::component/entries.updated')]);
    }
}
