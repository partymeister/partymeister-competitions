<?php

use Motor\Admin\Models\User;
use Partymeister\Competitions\Models\Option;
use Partymeister\Competitions\Models\OptionGroup;
use Spatie\Permission\Models\Role;

pest()->group('V2', 'OptionGroup');

beforeEach(function () {
    $role = Role::create(['name' => 'SuperAdmin', 'guard_name' => 'web']);
    $user = User::factory()->create([
        'email' => 'admin@motor-cms.com',
        'name' => 'Admin',
    ]);
    $user->assignRole($role);

    $group1 = OptionGroup::create(['name' => 'Engine', 'type' => 'single']);
    $group1->options()->create(['name' => 'Unity', 'sort_position' => 0]);
    $group1->options()->create(['name' => 'Unreal', 'sort_position' => 1]);

    OptionGroup::create(['name' => 'Platform', 'type' => 'multiple']);
});

describe('V2 OptionGroups API', function () {

    it('requires authentication', function () {
        assertV2RequiresAuth('/api/v2/option-groups');
    });

    it('includes api_version v2 in response meta', function () {
        $response = $this->asAdmin()->getJson('/api/v2/option-groups');

        $response->assertStatus(200)
            ->assertJsonPath('meta.api_version', 'v2');
    });

    it('can get all option groups', function () {
        assertV2CrudIndex('/api/v2/option-groups', 2, ['id', 'name', 'type']);
    });

    it('can get a specific option group with nested options', function () {
        $id = OptionGroup::where('name', 'Engine')->first()->id;

        $response = assertV2CrudShow(
            '/api/v2/option-groups/'.$id,
            ['id', 'name', 'type', 'options', 'created_at', 'updated_at']
        );

        $response->assertJsonCount(2, 'data.options');
        $response->assertJsonPath('data.options.0.name', 'Unity');
        $response->assertJsonPath('data.options.1.name', 'Unreal');
    });

    it('can create an option group with nested options', function () {
        $countBefore = OptionGroup::count();

        $response = $this->asAdmin()->postJson('/api/v2/option-groups', [
            'name' => 'AI Tool',
            'type' => 'single',
            'options' => [
                ['name' => 'None'],
                ['name' => 'Midjourney'],
                ['name' => 'Stable Diffusion'],
            ],
        ]);

        $response->assertCreated();
        assertV2ResponseEnvelope($response);
        expect(OptionGroup::count())->toBe($countBefore + 1);

        $response->assertJsonCount(3, 'data.options');
        $response->assertJsonPath('data.options.0.name', 'None');
        $response->assertJsonPath('data.options.1.name', 'Midjourney');
    });

    it('can create an option group without options', function () {
        assertV2CrudCreate('/api/v2/option-groups', [
            'name' => 'Empty Group',
            'type' => 'single',
        ], OptionGroup::class);
    });

    it('validates required fields on create', function () {
        $countBefore = OptionGroup::count();
        $this->asAdmin()
            ->withHeaders(['Accept' => 'application/json'])
            ->post('/api/v2/option-groups', [])
            ->assertStatus(422);
        expect(OptionGroup::count() - $countBefore)->toBe(0);
    });

    it('validates type is in allowed values', function () {
        $this->asAdmin()
            ->withHeaders(['Accept' => 'application/json'])
            ->postJson('/api/v2/option-groups', ['name' => 'Test', 'type' => 'invalid'])
            ->assertStatus(422);
    });

    it('can update an option group', function () {
        assertV2CrudUpdate(
            '/api/v2/option-groups/'.OptionGroup::first()->id,
            ['name' => 'Updated Engine'],
            'name',
            'Updated Engine'
        );
    });

    it('can update options on an option group', function () {
        $id = OptionGroup::where('name', 'Engine')->first()->id;

        $response = $this->asAdmin()->patchJson('/api/v2/option-groups/'.$id, [
            'options' => [
                ['name' => 'Godot'],
                ['name' => 'Bevy'],
            ],
        ]);

        $response->assertOk();
        $response->assertJsonCount(2, 'data.options');
        $response->assertJsonPath('data.options.0.name', 'Godot');

        expect(Option::where('option_group_id', $id)->count())->toBe(2);
    });

    it('can delete an option group with 204 No Content', function () {
        assertV2CrudDelete(
            '/api/v2/option-groups/'.OptionGroup::latest('id')->first()->id,
            OptionGroup::class
        );
    });
});
