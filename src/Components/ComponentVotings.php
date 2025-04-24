<?php

namespace Partymeister\Competitions\Components;

use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Motor\CMS\Models\PageVersionComponent;
use Partymeister\Competitions\Models\Competition;
use Partymeister\Competitions\Models\Component\ComponentVoting;
use Partymeister\Competitions\Models\LiveVote;
use Partymeister\Competitions\Models\Vote;

/**
 * Class ComponentVotings
 */
class ComponentVotings
{
    /**
     * @var ComponentVoting
     */
    protected $component;

    /**
     * @var PageVersionComponent
     */
    protected $pageVersionComponent;

    protected $request;

    protected $visitor;

    /**
     * @var array
     */
    protected $viewData = [];

    /**
     * ComponentVotings constructor.
     */
    public function __construct(
        PageVersionComponent $pageVersionComponent,
        ComponentVoting $component
    ) {
        $this->component = $component;
        $this->pageVersionComponent = $pageVersionComponent;
    }

    /**
     * @return Factory|RedirectResponse|Redirector|View
     */
    public function index(Request $request)
    {
        $this->visitor = Auth::guard('visitor')
            ->user();
        if (is_null($this->visitor)) {
            return redirect(route('frontend.pages.index', ['slug' => 'start']));
        }

        $this->request = $request;

        switch ($request->method()) {
            case 'POST':
                $result = $this->post();
                if ($result instanceof RedirectResponse) {
                    return $result;
                }
                break;
        }

        $votingDeadlineOver = false;
        if (strtotime(config('partymeister-competitions-voting.deadline')) < time()) {
            $votingDeadlineOver = true;
        }

        if ($request->get('competition_id') > 0) {
            $competition = Competition::where('voting_enabled', true)
                ->where('id', $request->get('competition_id'))
                ->orderBy('updated_at', 'ASC')
                ->first();
        } else {
            $competition = Competition::where('voting_enabled', true)
                ->orderBy('updated_at', 'ASC')
                ->first();
        }

        $votes = [];
        if (! is_null($competition)) {
            foreach ($competition->vote_categories as $voteCategory) {
                if (! isset($votes[$voteCategory->id])) {
                    $votes[$voteCategory->id] = [];
                }
            }
            foreach ($this->visitor->votes()
                ->where('competition_id', $competition->id)
                ->get() as $vote) {
                $votes[$vote->vote_category_id][$vote->entry_id] = [
                    'points' => $vote->points,
                    'comment' => $vote->comment,
                    'special_vote' => $vote->special_vote,
                ];
            }
        }

        // Check if livevoting is active
        $liveVoting = false;
        $liveVotingCompetition = '';
        $live = LiveVote::first();
        if (! is_null($live)) {
            if (! $live->competition->voting_enabled) {
                $liveVoting = true;
                $liveVotingCompetition = $live->competition->name;
            }
        }

        if (! is_null($competition)) {
            \View::share('activeCompetitionId', $competition->id);
        }

        $this->viewData = [
            'competition' => $competition,
            'votes' => $votes,
            'votingDeadlineOver' => $votingDeadlineOver,
            'liveVoting' => $liveVoting,
            'liveVotingCompetition' => $liveVotingCompetition,
        ];

        return $this->render();
    }

    /**
     * @return RedirectResponse
     */
    protected function post()
    {
        // Fixme: this is apparently never called
        foreach ($this->request->get('entry', []) as $competitionId => $voteCategories) {
            foreach ($voteCategories as $voteCategoryId => $entries) {
                foreach ($entries as $entryId => $points) {
                    $vote = $this->visitor->votes()
                        ->where('competition_id', $competitionId)
                        ->where('entry_id', $entryId)
                        ->where('vote_category_id', $voteCategoryId)
                        ->first();

                    if (is_null($vote)) {
                        $vote = new Vote;
                    }

                    if ($points > 5) {
                        $points = 5;
                    }

                    $vote->vote_category_id = $voteCategoryId;
                    $vote->competition_id = $competitionId;
                    $vote->entry_id = $entryId;
                    $vote->comment = Arr::get($this->request->all(), 'entry_comment.'.$competitionId.'.'.$entryId);
                    $vote->points = $points;
                    $vote->visitor_id = Auth::guard('visitor')
                        ->user()->id;
                    $vote->ip_address = $this->request->ip();
                    $vote->save();
                }
            }
        }

        flash()->success(trans('partymeister-competitions::backend/entries.created'));

        if ($this->request->get('competition_id', false)) {
            return redirect()->back();
            // return redirect('votes?competition_id=' . $this->request->get('competition_id'));
        } else {
            return redirect()->back();
        }
    }

    /**
     * @return Factory|View
     */
    public function render()
    {
        $this->viewData['component'] = $this->component;

        return view(config('motor-cms-page-components.components.'.$this->pageVersionComponent->component_name.'.view'), $this->viewData);
    }
}
