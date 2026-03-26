<?php

namespace Partymeister\Competitions\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Partymeister\Competitions\Models\Competition;
use Partymeister\Competitions\Models\LiveVote;

class ShaderShowdownController extends Controller
{
    /**
     * List all Shader Showdown competitions with entries.
     *
     * GET /api/shader-showdown/competitions
     */
    public function index(): JsonResponse
    {
        $competitions = Competition::with(['competition_type', 'entries' => function ($q) {
            $q->orderBy('sort_position', 'ASC');
        }])
            ->whereHas('competition_type', function ($q) {
                $q->whereIn('name', ['Shader Showdown', 'Shader Showdown Final']);
            })
            ->orderBy('sort_position', 'ASC')
            ->get();

        $liveVoteCompetitionIds = LiveVote::pluck('competition_id')->toArray();

        $data = $competitions->map(function ($comp) use ($liveVoteCompetitionIds) {
            return [
                'id' => $comp->id,
                'name' => $comp->name,
                'competition_type' => $comp->competition_type->name,
                'sort_position' => $comp->sort_position,
                'voting_enabled' => (bool) $comp->voting_enabled,
                'live_voting_enabled' => in_array($comp->id, $liveVoteCompetitionIds),
                'entry_count' => $comp->entries->count(),
            ];
        });

        return response()->json(['data' => $data]);
    }

    /**
     * Get single competition with entries and vote counts.
     *
     * GET /api/shader-showdown/competitions/{id}
     */
    public function show(int $id): JsonResponse
    {
        $competition = Competition::with(['competition_type', 'entries' => function ($q) {
            $q->orderBy('sort_position', 'ASC');
        }])
            ->whereHas('competition_type', function ($q) {
                $q->whereIn('name', ['Shader Showdown', 'Shader Showdown Final']);
            })
            ->findOrFail($id);

        $liveVote = LiveVote::where('competition_id', $id)->first();

        $entries = $competition->entries->map(function ($entry) {
            return [
                'id' => $entry->id,
                'title' => $entry->title,
                'author' => $entry->author,
                'sort_position' => $entry->sort_position,
                'status' => $entry->status,
                'votes' => round($entry->votes, 2),
            ];
        })->sortByDesc('votes')->values();

        $totalVotes = $entries->sum('votes');

        return response()->json([
            'data' => [
                'id' => $competition->id,
                'name' => $competition->name,
                'competition_type' => $competition->competition_type->name,
                'sort_position' => $competition->sort_position,
                'voting_enabled' => (bool) $competition->voting_enabled,
                'live_voting_enabled' => ! is_null($liveVote),
                'entries' => $entries,
                'total_votes' => round($totalVotes, 2),
            ],
        ]);
    }

    /**
     * Start live voting for a competition.
     *
     * POST /api/shader-showdown/competitions/{id}/start
     */
    public function start(int $id): JsonResponse
    {
        $competition = Competition::with(['competition_type', 'entries'])
            ->whereHas('competition_type', function ($q) {
                $q->whereIn('name', ['Shader Showdown', 'Shader Showdown Final']);
            })
            ->findOrFail($id);

        // Clear any existing live vote (only one can be active at a time)
        LiveVote::query()->delete();

        $lastEntry = $competition->entries()->orderBy('sort_position', 'DESC')->first();
        if (! $lastEntry) {
            return response()->json(['message' => 'Competition has no entries'], 422);
        }

        // Create LiveVote with sort_position = entry count (all entries voteable)
        LiveVote::create([
            'competition_id' => $competition->id,
            'entry_id' => $lastEntry->id,
            'sort_position' => $competition->entries->count(),
            'title' => $competition->name,
            'author' => $competition->name,
        ]);

        return response()->json(['message' => 'Live voting started for ' . $competition->name]);
    }

    /**
     * Stop live voting for a competition and disable voting.
     *
     * POST /api/shader-showdown/competitions/{id}/stop
     */
    public function stop(int $id): JsonResponse
    {
        $competition = Competition::whereHas('competition_type', function ($q) {
                $q->whereIn('name', ['Shader Showdown', 'Shader Showdown Final']);
            })
            ->findOrFail($id);

        $liveVote = LiveVote::where('competition_id', $id)->first();
        if (! $liveVote) {
            return response()->json(['message' => 'Live voting not active for this competition'], 409);
        }

        $liveVote->delete();

        $competition->update(['voting_enabled' => false]);

        return response()->json(['message' => 'Live voting stopped for ' . $competition->name]);
    }
}
