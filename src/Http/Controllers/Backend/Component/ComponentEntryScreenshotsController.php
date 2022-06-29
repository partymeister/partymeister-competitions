<?php

namespace Partymeister\Competitions\Http\Controllers\Backend\Component;

use Illuminate\Http\Request;
use Kris\LaravelFormBuilder\FormBuilderTrait;
use Motor\CMS\Http\Controllers\Component\ComponentController;
use Partymeister\Competitions\Forms\Backend\Component\ComponentEntryScreenshotForm;
use Partymeister\Competitions\Models\Component\ComponentEntryScreenshot;
use Partymeister\Competitions\Services\Component\ComponentEntryScreenshotService;

/**
 * Class ComponentEntryScreenshotsController
 */
class ComponentEntryScreenshotsController extends ComponentController
{
    use FormBuilderTrait;

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create()
    {
        $this->form = $this->form(ComponentEntryScreenshotForm::class);

        return response()->json($this->getFormData('component.entry-screenshots.store', ['mediapool' => false]));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $this->form = $this->form(ComponentEntryScreenshotForm::class);

        if (! $this->isValid()) {
            return $this->respondWithValidationError();
        }

        ComponentEntryScreenshotService::createWithForm($request, $this->form);

        return response()->json(['message' => trans('partymeister-competitions::component/entry-screenshots.created')]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  ComponentEntryScreenshot  $record
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(ComponentEntryScreenshot $record)
    {
        $this->form = $this->form(ComponentEntryScreenshotForm::class, [
            'model' => $record,
        ]);

        return response()->json($this->getFormData('component.entry-screenshots.update', ['mediapool' => false]));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  ComponentEntryScreenshot  $record
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, ComponentEntryScreenshot $record)
    {
        $form = $this->form(ComponentEntryScreenshotForm::class);

        if (! $this->isValid()) {
            return $this->respondWithValidationError();
        }

        ComponentEntryScreenshotService::updateWithForm($record, $request, $form);

        return response()->json(['message' => trans('partymeister-competitions::component/entry-screenshots.updated')]);
    }
}
