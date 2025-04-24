<?php

namespace Partymeister\Competitions\Http\Controllers\Backend\Component;

use Illuminate\Http\Request;
use Kris\LaravelFormBuilder\FormBuilderTrait;
use Motor\CMS\Http\Controllers\Component\ComponentController;
use Partymeister\Competitions\Forms\Backend\Component\ComponentEntryUploadForm;
use Partymeister\Competitions\Models\Component\ComponentEntryUpload;
use Partymeister\Competitions\Services\Component\ComponentEntryUploadService;

/**
 * Class ComponentEntryUploadsController
 */
class ComponentEntryUploadsController extends ComponentController
{
    use FormBuilderTrait;

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create()
    {
        $this->form = $this->form(ComponentEntryUploadForm::class);

        return response()->json($this->getFormData('component.entry-uploads.store', ['mediapool' => false]));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $this->form = $this->form(ComponentEntryUploadForm::class);

        if (! $this->isValid()) {
            return $this->respondWithValidationError();
        }

        ComponentEntryUploadService::createWithForm($request, $this->form);

        return response()->json(['message' => trans('partymeister-competitions::component/entry-uploads.created')]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(ComponentEntryUpload $record)
    {
        $this->form = $this->form(ComponentEntryUploadForm::class, [
            'model' => $record,
        ]);

        return response()->json($this->getFormData('component.entry-uploads.update', ['mediapool' => false]));
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, ComponentEntryUpload $record)
    {
        $form = $this->form(ComponentEntryUploadForm::class);

        if (! $this->isValid()) {
            return $this->respondWithValidationError();
        }

        ComponentEntryUploadService::updateWithForm($record, $request, $form);

        return response()->json(['message' => trans('partymeister-competitions::component/entry-uploads.updated')]);
    }
}
