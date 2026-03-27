<?php

use Motor\Admin\Models\User;
use Partymeister\Competitions\Models\Competition;
use Partymeister\Competitions\Models\CompetitionType;
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
});
