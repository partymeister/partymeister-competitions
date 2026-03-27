<?php

use Motor\Admin\Models\User;
use Partymeister\Competitions\Models\CompetitionType;
use Spatie\Permission\Models\Role;

pest()->group('V2', 'CompetitionType');

beforeEach(function () {
    $role = Role::create(['name' => 'SuperAdmin', 'guard_name' => 'web']);
    $user = User::factory()->create([
        'email' => 'admin@motor-cms.com',
        'name' => 'Admin',
    ]);
    $user->assignRole($role);

    CompetitionType::create(['name' => 'Demo']);
    CompetitionType::create(['name' => 'Executable']);
});

describe('V2 CompetitionTypes API', function () {

    it('requires authentication', function () {
        assertV2RequiresAuth('/api/v2/competition-types');
    });

    it('includes api_version v2 in response meta', function () {
        $response = $this->asAdmin()->getJson('/api/v2/competition-types');

        $response->assertStatus(200)
            ->assertJsonPath('meta.api_version', 'v2');
    });

    it('can get all competition types', function () {
        assertV2CrudIndex('/api/v2/competition-types', 2, ['id', 'name']);
    });

    it('can get a specific competition type', function () {
        assertV2CrudShow(
            '/api/v2/competition-types/'.CompetitionType::first()->id,
            ['id', 'name', 'has_platform', 'has_filesize', 'has_screenshot', 'has_video',
                'has_audio', 'has_recordings', 'has_composer', 'has_running_time', 'is_anonymous',
                'number_of_work_stages', 'has_remote_entries', 'file_is_optional', 'has_config_file',
                'has_ai_options', 'has_engine_options', 'has_out_of_competition_voting',
                'created_at', 'updated_at']
        );
    });

    it('can create a competition type', function () {
        assertV2CrudCreate('/api/v2/competition-types', [
            'name' => 'Music',
        ], CompetitionType::class);
    });

    it('validates required name on create', function () {
        $countBefore = CompetitionType::count();
        $this->asAdmin()
            ->withHeaders(['Accept' => 'application/json'])
            ->post('/api/v2/competition-types', [])
            ->assertStatus(422);
        expect(CompetitionType::count() - $countBefore)->toBe(0);
    });

    it('can update a competition type', function () {
        assertV2CrudUpdate(
            '/api/v2/competition-types/'.CompetitionType::first()->id,
            ['name' => 'Updated Demo'],
            'name',
            'Updated Demo'
        );
    });

    it('can update boolean flags on a competition type', function () {
        $id = CompetitionType::first()->id;

        $response = $this->asAdmin()->patchJson('/api/v2/competition-types/'.$id, [
            'has_platform' => true,
            'has_screenshot' => true,
        ]);

        $response->assertOk()
            ->assertJsonPath('data.has_platform', true)
            ->assertJsonPath('data.has_screenshot', true);
    });

    it('can delete a competition type with 204 No Content', function () {
        assertV2CrudDelete(
            '/api/v2/competition-types/'.CompetitionType::latest('id')->first()->id,
            CompetitionType::class
        );
    });
});
