<?php

use Motor\Admin\Models\User;
use Partymeister\Competitions\Models\Competition;
use Partymeister\Competitions\Models\CompetitionType;
use Partymeister\Competitions\Models\Entry;
use Partymeister\Competitions\Models\LiveVote;
use Partymeister\Competitions\Models\ManualVote;
use Partymeister\Competitions\Models\Vote;
use Partymeister\Competitions\Models\VoteCategory;
use Spatie\Permission\Models\Role;

pest()->group('V2', 'Vote');

beforeEach(function () {
    $role = Role::create(['name' => 'SuperAdmin', 'guard_name' => 'web']);
    $user = User::factory()->create([
        'email' => 'admin@motor-cms.com',
        'name' => 'Admin',
    ]);
    $user->assignRole($role);

    $competitionType = CompetitionType::factory()->create(['name' => 'Demo']);

    $competition = Competition::factory()->create([
        'name' => 'Test Competition',
        'competition_type_id' => $competitionType->id,
        'sort_position' => 1,
        'prizegiving_sort_position' => 1,
    ]);

    $entry = Entry::factory()->create([
        'competition_id' => $competition->id,
        'title' => 'Test Entry',
        'author' => 'Test Author',
        'sort_position' => 1,
    ]);

    $voteCategory = VoteCategory::factory()->create([
        'name' => 'Overall',
        'points' => 10,
    ]);

    $this->competition = $competition;
    $this->entry = $entry;
    $this->voteCategory = $voteCategory;
});

// ─────────────────────────────────────────────
// Votes
// ─────────────────────────────────────────────

describe('V2 Votes API', function () {

    it('requires authentication', function () {
        assertV2RequiresAuth('/api/v2/votes');
    });

    it('includes api_version v2 in response meta', function () {
        $response = $this->asAdmin()->getJson('/api/v2/votes');

        $response->assertStatus(200)
            ->assertJsonPath('meta.api_version', 'v2');
    });

    it('can get all votes (empty)', function () {
        assertV2CrudIndex('/api/v2/votes', 0, ['id', 'competition_id', 'entry_id', 'points']);
    });

    it('can get all votes with existing data', function () {
        Vote::factory()->create([
            'competition_id' => $this->competition->id,
            'entry_id' => $this->entry->id,
            'visitor_id' => null,
            'vote_category_id' => $this->voteCategory->id,
            'points' => 7,
            'special_vote' => false,
            'comment' => '',
            'ip_address' => '127.0.0.1',
        ]);

        assertV2CrudIndex('/api/v2/votes', 1, ['id', 'competition_id', 'entry_id', 'points', 'special_vote']);
    });

    it('can get a specific vote', function () {
        $vote = Vote::factory()->create([
            'competition_id' => $this->competition->id,
            'entry_id' => $this->entry->id,
            'visitor_id' => null,
            'vote_category_id' => $this->voteCategory->id,
            'points' => 5,
            'special_vote' => false,
            'comment' => '',
            'ip_address' => '127.0.0.1',
        ]);

        assertV2CrudShow('/api/v2/votes/'.$vote->id, ['id', 'competition_id', 'entry_id', 'points', 'special_vote', 'comment', 'ip_address']);
    });

    it('includes vote_category in show response', function () {
        $vote = Vote::factory()->create([
            'competition_id' => $this->competition->id,
            'entry_id' => $this->entry->id,
            'visitor_id' => null,
            'vote_category_id' => $this->voteCategory->id,
            'points' => 5,
            'special_vote' => false,
            'comment' => '',
            'ip_address' => '127.0.0.1',
        ]);

        $response = $this->asAdmin()->getJson('/api/v2/votes/'.$vote->id);

        $response->assertOk()
            ->assertJsonStructure(['data' => ['vote_category' => ['id', 'name']]])
            ->assertJsonPath('data.vote_category.name', 'Overall');
    });

    it('can create a vote', function () {
        assertV2CrudCreate('/api/v2/votes', [
            'competition_id' => $this->competition->id,
            'entry_id' => $this->entry->id,
            'vote_category_id' => $this->voteCategory->id,
            'points' => 8,
            'special_vote' => false,
            'comment' => 'Great entry',
            'ip_address' => '127.0.0.1',
        ], Vote::class);
    });

    it('validates required fields on vote create', function () {
        $this->asAdmin()
            ->withHeaders(['Accept' => 'application/json'])
            ->post('/api/v2/votes', [])
            ->assertStatus(422);
    });

    it('can update a vote', function () {
        $vote = Vote::factory()->create([
            'competition_id' => $this->competition->id,
            'entry_id' => $this->entry->id,
            'visitor_id' => null,
            'vote_category_id' => $this->voteCategory->id,
            'points' => 3,
            'special_vote' => false,
            'comment' => '',
            'ip_address' => '127.0.0.1',
        ]);

        assertV2CrudUpdate('/api/v2/votes/'.$vote->id, ['points' => 9], 'points', 9);
    });

    it('can delete a vote with 204 No Content', function () {
        $vote = Vote::factory()->create([
            'competition_id' => $this->competition->id,
            'entry_id' => $this->entry->id,
            'visitor_id' => null,
            'vote_category_id' => $this->voteCategory->id,
            'points' => 4,
            'special_vote' => false,
            'comment' => '',
            'ip_address' => '127.0.0.1',
        ]);

        assertV2CrudDelete('/api/v2/votes/'.$vote->id, Vote::class);
    });
});

// ─────────────────────────────────────────────
// ManualVotes
// ─────────────────────────────────────────────

describe('V2 ManualVotes API', function () {

    it('requires authentication', function () {
        assertV2RequiresAuth('/api/v2/manual-votes');
    });

    it('includes api_version v2 in response meta', function () {
        $response = $this->asAdmin()->getJson('/api/v2/manual-votes');

        $response->assertStatus(200)
            ->assertJsonPath('meta.api_version', 'v2');
    });

    it('can get all manual votes (empty)', function () {
        assertV2CrudIndex('/api/v2/manual-votes', 0, ['id', 'competition_id', 'entry_id', 'points']);
    });

    it('can get all manual votes with existing data', function () {
        ManualVote::factory()->create([
            'competition_id' => $this->competition->id,
            'entry_id' => $this->entry->id,
            'points' => 5,
            'ip_address' => '127.0.0.1',
        ]);

        assertV2CrudIndex('/api/v2/manual-votes', 1, ['id', 'competition_id', 'entry_id', 'points']);
    });

    it('can get a specific manual vote', function () {
        $manualVote = ManualVote::factory()->create([
            'competition_id' => $this->competition->id,
            'entry_id' => $this->entry->id,
            'points' => 6,
            'ip_address' => '127.0.0.1',
        ]);

        assertV2CrudShow('/api/v2/manual-votes/'.$manualVote->id, ['id', 'competition_id', 'entry_id', 'points', 'ip_address']);
    });

    it('can create a manual vote', function () {
        assertV2CrudCreate('/api/v2/manual-votes', [
            'competition_id' => $this->competition->id,
            'entry_id' => $this->entry->id,
            'points' => 7,
            'ip_address' => '127.0.0.1',
        ], ManualVote::class);
    });

    it('validates required fields on manual vote create', function () {
        $this->asAdmin()
            ->withHeaders(['Accept' => 'application/json'])
            ->post('/api/v2/manual-votes', [])
            ->assertStatus(422);
    });

    it('can update a manual vote', function () {
        $manualVote = ManualVote::factory()->create([
            'competition_id' => $this->competition->id,
            'entry_id' => $this->entry->id,
            'points' => 2,
            'ip_address' => '127.0.0.1',
        ]);

        assertV2CrudUpdate('/api/v2/manual-votes/'.$manualVote->id, ['points' => 10], 'points', 10);
    });

    it('can delete a manual vote with 204 No Content', function () {
        $manualVote = ManualVote::factory()->create([
            'competition_id' => $this->competition->id,
            'entry_id' => $this->entry->id,
            'points' => 3,
            'ip_address' => '127.0.0.1',
        ]);

        assertV2CrudDelete('/api/v2/manual-votes/'.$manualVote->id, ManualVote::class);
    });
});

// ─────────────────────────────────────────────
// LiveVotes
// ─────────────────────────────────────────────

describe('V2 LiveVotes API', function () {

    it('requires authentication', function () {
        assertV2RequiresAuth('/api/v2/live-votes');
    });

    it('includes api_version v2 in response meta', function () {
        $response = $this->asAdmin()->getJson('/api/v2/live-votes');

        $response->assertStatus(200)
            ->assertJsonPath('meta.api_version', 'v2');
    });

    it('can get all live votes (empty)', function () {
        assertV2CrudIndex('/api/v2/live-votes', 0, ['id', 'sort_position', 'title', 'author']);
    });

    it('can get all live votes with existing data', function () {
        LiveVote::factory()->create([
            'competition_id' => $this->competition->id,
            'entry_id' => $this->entry->id,
            'sort_position' => 1,
            'title' => 'Amazing Demo',
            'author' => 'Demoscene Artist',
        ]);

        assertV2CrudIndex('/api/v2/live-votes', 1, ['id', 'sort_position', 'title', 'author']);
    });

    it('can get a specific live vote', function () {
        $liveVote = LiveVote::factory()->create([
            'competition_id' => $this->competition->id,
            'entry_id' => $this->entry->id,
            'sort_position' => 2,
            'title' => 'Cool Intro',
            'author' => 'Some Coder',
        ]);

        assertV2CrudShow('/api/v2/live-votes/'.$liveVote->id, ['id', 'sort_position', 'title', 'author']);
    });

    it('can create a live vote', function () {
        assertV2CrudCreate('/api/v2/live-votes', [
            'competition_id' => $this->competition->id,
            'entry_id' => $this->entry->id,
            'sort_position' => 3,
            'title' => 'New Entry',
            'author' => 'New Author',
        ], LiveVote::class);
    });

    it('validates required fields on live vote create', function () {
        $this->asAdmin()
            ->withHeaders(['Accept' => 'application/json'])
            ->post('/api/v2/live-votes', [])
            ->assertStatus(422);
    });

    it('can update a live vote', function () {
        $liveVote = LiveVote::factory()->create([
            'competition_id' => $this->competition->id,
            'entry_id' => $this->entry->id,
            'sort_position' => 5,
            'title' => 'Old Title',
            'author' => 'Old Author',
        ]);

        assertV2CrudUpdate('/api/v2/live-votes/'.$liveVote->id, ['title' => 'Updated Title'], 'title', 'Updated Title');
    });

    it('can delete a live vote with 204 No Content', function () {
        $liveVote = LiveVote::factory()->create([
            'competition_id' => $this->competition->id,
            'entry_id' => $this->entry->id,
            'sort_position' => 4,
            'title' => 'To Delete',
            'author' => 'Some Author',
        ]);

        assertV2CrudDelete('/api/v2/live-votes/'.$liveVote->id, LiveVote::class);
    });
});
