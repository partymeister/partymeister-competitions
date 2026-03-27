<?php

use Motor\Admin\Models\User;
use Partymeister\Competitions\Models\Competition;
use Partymeister\Competitions\Models\CompetitionPrize;
use Spatie\Permission\Models\Role;

pest()->group('V2', 'CompetitionPrize');

beforeEach(function () {
    $role = Role::create(['name' => 'SuperAdmin', 'guard_name' => 'web']);
    $user = User::factory()->create([
        'email' => 'admin@motor-cms.com',
        'name' => 'Admin',
    ]);
    $user->assignRole($role);

    $competition = Competition::factory()->create(['name' => 'Demo Competition']);

    CompetitionPrize::factory()->create([
        'competition_id' => $competition->id,
        'amount' => '500',
        'rank' => 1,
    ]);
    CompetitionPrize::factory()->create([
        'competition_id' => $competition->id,
        'amount' => '250',
        'rank' => 2,
    ]);
});

describe('V2 Competition Prizes API', function () {

    it('includes api_version v2 in response meta', function () {
        $response = $this->asAdmin()->getJson('/api/v2/competition-prizes');

        $response->assertStatus(200)
            ->assertJsonPath('meta.api_version', 'v2');
    });

    it('can get all competition prizes', function () {
        assertV2CrudIndex('/api/v2/competition-prizes', 2, ['id', 'amount', 'rank']);
    });

    it('can get a specific competition prize', function () {
        assertV2CrudShow(
            '/api/v2/competition-prizes/'.CompetitionPrize::first()->id,
            ['id', 'amount', 'additional', 'rank', 'created_at', 'updated_at']
        );
    });

    it('includes competition in show response', function () {
        $prize = CompetitionPrize::first();

        $response = $this->asAdmin()->getJson('/api/v2/competition-prizes/'.$prize->id);

        $response->assertOk()
            ->assertJsonStructure(['data' => ['competition' => ['id', 'name']]])
            ->assertJsonPath('data.competition.name', 'Demo Competition');
    });

    it('can create a competition prize', function () {
        $competition = Competition::first();
        assertV2CrudCreate('/api/v2/competition-prizes', [
            'competition_id' => $competition->id,
            'amount' => '100',
            'additional' => '',
            'rank' => 3,
        ], CompetitionPrize::class);
    });

    it('validates required fields on create', function () {
        $countBefore = CompetitionPrize::count();
        $this->asAdmin()
            ->withHeaders(['Accept' => 'application/json'])
            ->post('/api/v2/competition-prizes', [])
            ->assertStatus(422);
        expect(CompetitionPrize::count() - $countBefore)->toBe(0);
    });

    it('can update a competition prize', function () {
        assertV2CrudUpdate(
            '/api/v2/competition-prizes/'.CompetitionPrize::first()->id,
            ['amount' => '750'],
            'amount',
            '750'
        );
    });

    it('can delete a competition prize with 204 No Content', function () {
        assertV2CrudDelete(
            '/api/v2/competition-prizes/'.CompetitionPrize::latest('id')->first()->id,
            CompetitionPrize::class
        );
    });
});
