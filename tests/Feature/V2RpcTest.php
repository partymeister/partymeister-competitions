<?php

use Motor\Admin\Models\User;
use Partymeister\Competitions\Models\AccessKey;
use Spatie\Permission\Models\Role;

pest()->group('V2', 'Rpc');

beforeEach(function () {
    $role = Role::create(['name' => 'SuperAdmin', 'guard_name' => 'web']);
    $user = User::factory()->create([
        'email' => 'admin@motor-cms.com',
        'name' => 'Admin',
    ]);
    $user->assignRole($role);

    config([
        'partymeister-competitions-access-key.chars' => ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'],
        'partymeister-competitions-access-key.length' => 8,
        'partymeister-competitions-access-key.divider' => '-',
        'partymeister-competitions-access-key.divide_every' => 4,
    ]);
});

// ─────────────────────────────────────────────
// Vote Results RPC
// ─────────────────────────────────────────────

describe('V2 RPC Vote Results', function () {

    it('requires authentication', function () {
        assertV2RequiresAuth('/api/v2/rpc/votes/results');
    });

    it('returns 200 with api_version v2', function () {
        $response = $this->asAdmin()->getJson('/api/v2/rpc/votes/results');

        $response->assertStatus(200)
            ->assertJsonPath('meta.api_version', 'v2');
    });

    it('response has data.results as array', function () {
        $response = $this->asAdmin()->getJson('/api/v2/rpc/votes/results');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'results',
                    'special',
                ],
                'meta' => [
                    'api_version',
                    'message',
                ],
            ]);

        expect($response->json('data.results'))->toBeArray();
        expect($response->json('data.special'))->toBeArray();
    });

    it('returns message in meta', function () {
        $response = $this->asAdmin()->getJson('/api/v2/rpc/votes/results');

        $response->assertStatus(200)
            ->assertJsonPath('meta.message', 'Vote results retrieved');
    });
});

// ─────────────────────────────────────────────
// Access Key Generation RPC
// ─────────────────────────────────────────────

describe('V2 RPC Access Key Generation', function () {

    it('requires authentication', function () {
        assertV2RequiresAuth('/api/v2/rpc/access-keys/generate', 'post');
    });

    it('generates access keys and returns 201', function () {
        $countBefore = AccessKey::count();

        $response = $this->asAdmin()
            ->withHeaders(['Accept' => 'application/json'])
            ->postJson('/api/v2/rpc/access-keys/generate', ['quantity' => 5]);

        $response->assertStatus(201)
            ->assertJsonPath('meta.api_version', 'v2');

        // Service deletes unassigned non-prepaid keys before generating new ones,
        // so we verify 5 keys now exist in the DB (not a net +5 delta)
        expect(AccessKey::count())->toBe(5);
        expect($response->json('data.generated'))->toBe(5);
    });

    it('validates quantity is required', function () {
        $response = $this->asAdmin()
            ->withHeaders(['Accept' => 'application/json'])
            ->postJson('/api/v2/rpc/access-keys/generate', []);

        $response->assertStatus(422)
            ->assertJsonPath('error.code', 'VALIDATION_ERROR')
            ->assertJsonPath('meta.api_version', 'v2');

        expect($response->json('error.details'))->toHaveKey('quantity');
    });

    it('validates quantity must be at least 1', function () {
        $response = $this->asAdmin()
            ->withHeaders(['Accept' => 'application/json'])
            ->postJson('/api/v2/rpc/access-keys/generate', ['quantity' => 0]);

        $response->assertStatus(422)
            ->assertJsonPath('error.code', 'VALIDATION_ERROR');

        expect($response->json('error.details'))->toHaveKey('quantity');
    });

    it('validates quantity max is 2000', function () {
        $response = $this->asAdmin()
            ->withHeaders(['Accept' => 'application/json'])
            ->postJson('/api/v2/rpc/access-keys/generate', ['quantity' => 2001]);

        $response->assertStatus(422)
            ->assertJsonPath('error.code', 'VALIDATION_ERROR');

        expect($response->json('error.details'))->toHaveKey('quantity');
    });

    it('validates quantity must be integer', function () {
        $response = $this->asAdmin()
            ->withHeaders(['Accept' => 'application/json'])
            ->postJson('/api/v2/rpc/access-keys/generate', ['quantity' => 'abc']);

        $response->assertStatus(422)
            ->assertJsonPath('error.code', 'VALIDATION_ERROR');

        expect($response->json('error.details'))->toHaveKey('quantity');
    });

    it('returns generated count in data', function () {
        $response = $this->asAdmin()
            ->withHeaders(['Accept' => 'application/json'])
            ->postJson('/api/v2/rpc/access-keys/generate', ['quantity' => 3]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => ['generated'],
                'meta' => ['api_version', 'message'],
            ]);

        expect($response->json('data.generated'))->toBe(3);
    });
});
