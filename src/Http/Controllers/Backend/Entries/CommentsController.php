<?php

namespace Partymeister\Competitions\Http\Controllers\Backend\Entries;

use Illuminate\Http\Request;
use Kris\LaravelFormBuilder\FormBuilderTrait;
use Motor\Backend\Http\Controllers\Controller;
use Partymeister\Competitions\Forms\Backend\CommentForm;
use Partymeister\Competitions\Models\Comment;
use Partymeister\Competitions\Models\Entry;

/**
 * Class CommentsController
 *
 * @package Partymeister\Competitions\Http\Controllers\Backend\Entries
 */
class CommentsController extends Controller
{
    use FormBuilderTrait;

    /**
     * Display a listing of the resource.
     *
     * @param Entry $record
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Entry $record)
    {
        $form = $this->form(CommentForm::class, [
            'method'  => 'POST',
            'url'     => route('backend.entries.comments.store', [$record->id]),
            'enctype' => 'multipart/form-data',
            'model'   => $record,
        ]);

        $comments = $record->comments;

        return view('partymeister-competitions::backend.entries.comments.index', compact('form', 'record', 'comments'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param Entry $record
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request, Entry $record)
    {
        $form = $this->form(CommentForm::class);

        if ($request->get('mark_as_read') == 1) {
            foreach ($record->comments()
                            ->get() as $comment) {
                $comment->read_by_organizer = true;
                $comment->save();
            }

            return redirect('backend/entries/comments/'.$record->id);
        } else {
            $form->getField('message')
                 ->setOption('rules', ['required']);
        }

        if (! $form->isValid()) {
            return redirect()
                ->back()
                ->withErrors($form->getErrors())
                ->withInput();
        }

        foreach ($record->comments()
                        ->get() as $comment) {
            $comment->read_by_organizer = true;
            $comment->save();
        }

        $c = new Comment();
        $c->visitor_id = $record->visitor_id;
        $c->read_by_organizer = true;
        $c->model_type = get_class($record);
        $c->model_id = $record->id;
        $c->author = 'Revision Organizing';
        $c->message = $request->get('message');
        $c->save();

        flash()->success(trans('partymeister-competitions::backend/entries.comment_created'));

        return redirect('backend/entries/comments/'.$record->id);
    }
}
