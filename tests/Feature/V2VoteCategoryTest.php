<?php

use Motor\Admin\Models\User;
use Partymeister\Competitions\Models\VoteCategory;
use Spatie\Permission\Models\Role;

pest()->group('V2', 'VoteCategory');

beforeEach(function () {
    $role = Role::create(['name' => 'SuperAdmin', 'guard_name' => 'web']);
    $user = User::factory()->create([
        'email' => 'admin@motor-cms.com',
        'name' => 'Admin',
    ]);
    $user->assignRole($role);

    VoteCategory::create(['name' => 'Overall', 'points' => 10]);
    VoteCategory::create(['name' => 'Technical', 'points' => 5]);
});

describe('V2 VoteCategories API', function () {

    it('includes api_version v2 in response meta', function () {
        $response = $this->asAdmin()->getJson('/api/v2/vote-categories');

        $response->assertStatus(200)
            ->assertJsonPath('meta.api_version', 'v2');
    });

    it('can get all vote categories', function () {
        assertV2CrudIndex('/api/v2/vote-categories', 2, ['id', 'name', 'points']);
    });

    it('can get a specific vote category', function () {
        assertV2CrudShow(
            '/api/v2/vote-categories/'.VoteCategory::first()->id,
            ['id', 'name', 'points', 'has_negative', 'has_comment', 'has_special_vote',
                'created_at', 'updated_at']
        );
    });

    it('can create a vote category', function () {
        assertV2CrudCreate('/api/v2/vote-categories', [
            'name' => 'Originality',
            'points' => 8,
        ], VoteCategory::class);
    });

    it('validates required fields on create', function () {
        $countBefore = VoteCategory::count();
        $this->asAdmin()
            ->withHeaders(['Accept' => 'application/json'])
            ->post('/api/v2/vote-categories', [])
            ->assertStatus(422);
        expect(VoteCategory::count() - $countBefore)->toBe(0);
    });

    it('validates points is required on create', function () {
        $this->asAdmin()
            ->withHeaders(['Accept' => 'application/json'])
            ->postJson('/api/v2/vote-categories', ['name' => 'No Points'])
            ->assertStatus(422);
    });

    it('can update a vote category', function () {
        assertV2CrudUpdate(
            '/api/v2/vote-categories/'.VoteCategory::first()->id,
            ['name' => 'Updated Overall'],
            'name',
            'Updated Overall'
        );
    });

    it('can delete a vote category with 204 No Content', function () {
        assertV2CrudDelete(
            '/api/v2/vote-categories/'.VoteCategory::latest('id')->first()->id,
            VoteCategory::class
        );
    });
});
