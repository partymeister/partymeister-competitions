<?php

use Motor\Admin\Models\User;
use Partymeister\Competitions\Models\Competition;
use Partymeister\Competitions\Models\Entry;
use Spatie\Permission\Models\Role;

pest()->group('V2', 'Entry');

beforeEach(function () {
    $role = Role::create(['name' => 'SuperAdmin', 'guard_name' => 'web']);
    $user = User::factory()->create([
        'email' => 'admin@motor-cms.com',
        'name'  => 'Admin',
    ]);
    $user->assignRole($role);

    $competition = Competition::factory()->create(['name' => 'Demo Competition']);

    Entry::factory()->create([
        'competition_id' => $competition->id,
        'title'          => 'My Demo',
        'author'         => 'Demo Author',
    ]);
    Entry::factory()->create([
        'competition_id' => $competition->id,
        'title'          => 'Another Demo',
        'author'         => 'Another Author',
    ]);
});

describe('V2 Entries API', function () {

    it('includes api_version v2 in response meta', function () {
        $response = $this->asAdmin()->getJson('/api/v2/entries');

        $response->assertStatus(200)
            ->assertJsonPath('meta.api_version', 'v2');
    });

    it('can get all entries', function () {
        assertV2CrudIndex('/api/v2/entries', 2, ['id', 'title', 'author']);
    });

    it('can get a specific entry', function () {
        assertV2CrudShow(
            '/api/v2/entries/'.Entry::first()->id,
            ['id', 'title', 'author', 'identifier', 'sort_position', 'status',
                'allow_release', 'is_remote', 'is_recorded', 'created_at', 'updated_at']
        );
    });

    it('can create an entry', function () {
        $competition = Competition::first();
        assertV2CrudCreate('/api/v2/entries', [
            'competition_id' => $competition->id,
            'title'          => 'New Entry',
            'author'         => 'New Author',
            'sort_position'  => 0,
        ], Entry::class);
    });

    it('validates required fields on create', function () {
        $countBefore = Entry::count();
        $this->asAdmin()
            ->withHeaders(['Accept' => 'application/json'])
            ->post('/api/v2/entries', [])
            ->assertStatus(422);
        expect(Entry::count() - $countBefore)->toBe(0);
    });

    it('can update an entry', function () {
        assertV2CrudUpdate(
            '/api/v2/entries/'.Entry::first()->id,
            ['title' => 'Updated Title'],
            'title',
            'Updated Title'
        );
    });

    it('can delete an entry with 204 No Content', function () {
        assertV2CrudDelete(
            '/api/v2/entries/'.Entry::latest('id')->first()->id,
            Entry::class
        );
    });
});
