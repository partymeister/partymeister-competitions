<?php

use Motor\Admin\Models\User;
use Partymeister\Competitions\Models\Competition;
use Partymeister\Competitions\Models\CompetitionPrize;
use Partymeister\Competitions\Models\CompetitionType;
use Partymeister\Competitions\Models\Entry;
use Partymeister\Competitions\Models\OptionGroup;
use Partymeister\Competitions\Models\VoteCategory;
use Spatie\Permission\Models\Role;

pest()->group('V2', 'Competition');

beforeEach(function () {
    $role = Role::create(['name' => 'SuperAdmin', 'guard_name' => 'web']);
    $user = User::factory()->create([
        'email' => 'admin@motor-cms.com',
        'name' => 'Admin',
    ]);
    $user->assignRole($role);

    $type = CompetitionType::factory()->create(['name' => 'Demo']);

    Competition::factory()->create([
        'name' => 'Oldskool Demo',
        'competition_type_id' => $type->id,
    ]);
    Competition::factory()->create([
        'name' => 'Newskool Demo',
        'competition_type_id' => $type->id,
    ]);
});

describe('V2 Competitions API', function () {

    it('includes api_version v2 in response meta', function () {
        $response = $this->asAdmin()->getJson('/api/v2/competitions');

        $response->assertStatus(200)
            ->assertJsonPath('meta.api_version', 'v2');
    });

    it('can get all competitions', function () {
        assertV2CrudIndex('/api/v2/competitions', 2, ['id', 'name']);
    });

    it('can get a specific competition', function () {
        assertV2CrudShow(
            '/api/v2/competitions/'.Competition::first()->id,
            ['id', 'name', 'has_prizegiving', 'upload_enabled', 'voting_enabled',
                'sort_position', 'prizegiving_sort_position', 'created_at', 'updated_at']
        );
    });

    it('can create a competition', function () {
        $type = CompetitionType::first();
        assertV2CrudCreate('/api/v2/competitions', [
            'name' => 'Music',
            'competition_type_id' => $type->id,
            'sort_position' => 0,
            'prizegiving_sort_position' => 0,
        ], Competition::class);
    });

    it('validates required fields on create', function () {
        $countBefore = Competition::count();
        $this->asAdmin()
            ->withHeaders(['Accept' => 'application/json'])
            ->post('/api/v2/competitions', [])
            ->assertStatus(422);
        expect(Competition::count() - $countBefore)->toBe(0);
    });

    it('can update a competition', function () {
        assertV2CrudUpdate(
            '/api/v2/competitions/'.Competition::first()->id,
            ['name' => 'Updated Demo'],
            'name',
            'Updated Demo'
        );
    });

    it('can delete a competition with 204 No Content', function () {
        assertV2CrudDelete(
            '/api/v2/competitions/'.Competition::latest('id')->first()->id,
            Competition::class
        );
    });

    it('includes competition_type in show response', function () {
        $competition = Competition::first();

        $response = $this->asAdmin()->getJson('/api/v2/competitions/'.$competition->id);

        $response->assertOk()
            ->assertJsonStructure(['data' => ['competition_type' => ['id', 'name']]]);
    });

    it('includes vote_categories as array in show response', function () {
        $voteCategory = VoteCategory::create([
            'name' => 'Overall',
            'points' => 10,
            'has_negative' => false,
            'has_comment' => false,
            'has_special_vote' => false,
        ]);
        $competition = Competition::first();
        $competition->vote_categories()->attach($voteCategory->id);

        $response = $this->asAdmin()->getJson('/api/v2/competitions/'.$competition->id);

        $response->assertOk()
            ->assertJsonStructure(['data' => ['vote_categories']])
            ->assertJsonCount(1, 'data.vote_categories')
            ->assertJsonPath('data.vote_categories.0.name', 'Overall');
    });

    it('includes option_groups as array in show response', function () {
        $optionGroup = OptionGroup::create(['name' => 'Engine', 'type' => 'single']);
        $competition = Competition::first();
        $competition->option_groups()->attach($optionGroup->id);

        $response = $this->asAdmin()->getJson('/api/v2/competitions/'.$competition->id);

        $response->assertOk()
            ->assertJsonStructure(['data' => ['option_groups']])
            ->assertJsonCount(1, 'data.option_groups')
            ->assertJsonPath('data.option_groups.0.name', 'Engine');
    });

    it('can get entries for a competition', function () {
        $competition = Competition::first();

        $response = $this->asAdmin()->getJson('/api/v2/competitions/'.$competition->id.'/entries');

        $response->assertOk()
            ->assertJsonPath('meta.api_version', 'v2');
    });

    it('can get prizes for a competition', function () {
        $competition = Competition::first();

        $response = $this->asAdmin()->getJson('/api/v2/competitions/'.$competition->id.'/prizes');

        $response->assertOk()
            ->assertJsonPath('meta.api_version', 'v2');
    });

    it('returns entries data for a competition with entries', function () {
        $competition = Competition::first();
        Entry::factory()->create([
            'competition_id' => $competition->id,
            'title' => 'Test Entry',
            'author' => 'Test Author',
        ]);

        $response = $this->asAdmin()->getJson('/api/v2/competitions/'.$competition->id.'/entries');

        $response->assertOk()
            ->assertJsonPath('meta.api_version', 'v2')
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.title', 'Test Entry');
    });

    it('returns prizes data for a competition with prizes', function () {
        $competition = Competition::first();
        CompetitionPrize::factory()->create([
            'competition_id' => $competition->id,
            'amount' => '500',
            'rank' => 1,
        ]);

        $response = $this->asAdmin()->getJson('/api/v2/competitions/'.$competition->id.'/prizes');

        $response->assertOk()
            ->assertJsonPath('meta.api_version', 'v2')
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.rank', 1);
    });
});
