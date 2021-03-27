<?php

namespace Partymeister\Competitions\Http\Controllers\Backend\Votes;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Motor\Backend\Http\Controllers\Controller;
use Partymeister\Competitions\Services\VoteService;
use Partymeister\Slides\Models\SlideTemplate;
use Partymeister\Slides\Services\PlaylistService;

/**
 * Class PlaylistsController
 * @package Partymeister\Competitions\Http\Controllers\Backend\Votes
 */
class PlaylistsController extends Controller
{

    /**
     * @param  Request  $request
     * @return RedirectResponse|Redirector
     */
    public function store(Request $request)
    {
        PlaylistService::generatePrizegivingPlaylist($request->all());

        return redirect(route('backend.votes.index'));
    }


    /**
     * Display a listing of the resource.
     *
     * @param  Request  $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $results      = VoteService::getAllVotesByRank('ASC');
        $specialVotes = VoteService::getAllSpecialVotesByRank();

        foreach ($specialVotes as $entryKey => $entry) {
            if ($entryKey > config('partymeister-slides-prizegiving.entries')-1) {
                unset($specialVotes[$entryKey]);
            }
        }
        shuffle($specialVotes);

        $comments = [];
        foreach ($results as $competition) {
            $comments[$competition['id']] = [];
            foreach ($competition['entries'] as $entry) {
                foreach ($entry['comments'] as $comment) {
                    if (strlen($comment) < 30) {
                        $comments[$competition['id']][] = $comment;
                        $comments[$competition['id']]   = array_unique($comments[$competition['id']]);
                    }
                }
            }
            shuffle($comments[$competition['id']]);
            $chunks = array_chunk($comments[$competition['id']], 8);
            if (count($chunks) > 0) {
                $comments[$competition['id']] = $chunks[0];
            } else {
                $comments[$competition['id']] = [];
            }
            $comments[$competition['id']] = implode('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
                $comments[$competition['id']]);
        }

        $prizegivingTemplate = SlideTemplate::where('template_for', 'prizegiving')->first();
        $comingupTemplate    = SlideTemplate::where('template_for', 'coming_up')->first();
        $nowTemplate         = SlideTemplate::where('template_for', 'now')->first();
        $endTemplate         = SlideTemplate::where('template_for', 'end_of_pg')->first();
        $commentsTemplate    = SlideTemplate::where('template_for', 'comments')->first();

        foreach ($results as $key => $competition) {
            foreach ($competition['entries'] as $entryKey => $entry) {
                if ($entryKey > config('partymeister-slides-prizegiving.entries')-1) {
                    unset($results[$key]['entries'][$entryKey]);
                }
            }
            shuffle($results[$key]['entries']);
        }

        $renderSupport = false;
        if ($request->get('support')) {
            $renderSupport = true;
        }
        $renderNow = false;
        if ($request->get('now')) {
            $renderNow = true;
        }
        $renderCompetitions = false;
        if ($request->get('competitions')) {
            $renderCompetitions = true;
        }
        $renderComments = false;
        if ($request->get('comments')) {
            $renderComments = true;
        }
        $renderBars = false;
        if ($request->get('bars')) {
            $renderBars = true;
        }
        $renderWinners = false;
        if ($request->get('winners')) {
            $renderWinners = true;
        }

        $renderSupport      = true;
        $renderNow          = true;
        $renderCompetitions = true;
        $renderComments     = true;
        $renderBars         = true;
        $renderWinners      = true;

        return view('partymeister-competitions::backend.votes.playlists.show',
            compact('results', 'comments', 'commentsTemplate', 'specialVotes', 'prizegivingTemplate',
                'comingupTemplate', 'nowTemplate', 'endTemplate', 'renderSupport', 'renderCompetitions', 'renderBars', 'renderWinners',
                'renderComments', 'renderNow'));
    }
}
