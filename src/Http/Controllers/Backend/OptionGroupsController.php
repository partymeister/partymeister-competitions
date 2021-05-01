<?php

namespace Partymeister\Competitions\Http\Controllers\Backend;

use Illuminate\Http\Response;
use Kris\LaravelFormBuilder\FormBuilderTrait;
use Motor\Backend\Http\Controllers\Controller;
use Partymeister\Competitions\Forms\Backend\OptionGroupForm;
use Partymeister\Competitions\Grids\OptionGroupGrid;
use Partymeister\Competitions\Http\Requests\Backend\OptionGroupRequest;
use Partymeister\Competitions\Models\OptionGroup;
use Partymeister\Competitions\Services\OptionGroupService;

/**
 * Class OptionGroupsController
 *
 * @package Partymeister\Competitions\Http\Controllers\Backend
 */
class OptionGroupsController extends Controller
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
        $grid = new OptionGroupGrid(OptionGroup::class);

        $service = OptionGroupService::collection($grid);
        $grid->setFilter($service->getFilter());
        $paginator = $service->getPaginator();

        return view('partymeister-competitions::backend.option_groups.index', compact('paginator', 'grid'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $form = $this->form(OptionGroupForm::class, [
            'method'  => 'POST',
            'route'   => 'backend.option_groups.store',
            'enctype' => 'multipart/form-data',
        ]);

        return view('partymeister-competitions::backend.option_groups.create', compact('form'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param OptionGroupRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(OptionGroupRequest $request)
    {
        $form = $this->form(OptionGroupForm::class);

        // It will automatically use current request, get the rules, and do the validation
        if (! $form->isValid()) {
            return redirect()
                ->back()
                ->withErrors($form->getErrors())
                ->withInput();
        }

        OptionGroupService::createWithForm($request, $form);

        flash()->success(trans('partymeister-competitions::backend/option_groups.created'));

        return redirect('backend/option_groups');
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
     * @param OptionGroup $record
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(OptionGroup $record)
    {
        $form = $this->form(OptionGroupForm::class, [
            'method'  => 'PATCH',
            'url'     => route('backend.option_groups.update', [$record->id]),
            'enctype' => 'multipart/form-data',
            'model'   => $record,
        ]);

        return view('partymeister-competitions::backend.option_groups.edit', compact('form'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param OptionGroupRequest $request
     * @param OptionGroup $record
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(OptionGroupRequest $request, OptionGroup $record)
    {
        $form = $this->form(OptionGroupForm::class);

        // It will automatically use current request, get the rules, and do the validation
        if (! $form->isValid()) {
            return redirect()
                ->back()
                ->withErrors($form->getErrors())
                ->withInput();
        }

        OptionGroupService::updateWithForm($record, $request, $form);

        flash()->success(trans('partymeister-competitions::backend/option_groups.updated'));

        return redirect('backend/option_groups');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param OptionGroup $record
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy(OptionGroup $record)
    {
        OptionGroupService::delete($record);

        flash()->success(trans('partymeister-competitions::backend/option_groups.deleted'));

        return redirect('backend/option_groups');
    }
}
