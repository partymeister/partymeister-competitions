<?php

use Motor\Admin\Models\User;
use Partymeister\Competitions\Models\AccessKey;
use Spatie\Permission\Models\Role;

pest()->group('V2', 'AccessKey');

beforeEach(function () {
    $role = Role::create(['name' => 'SuperAdmin', 'guard_name' => 'web']);
    $user = User::factory()->create([
        'email' => 'admin@motor-cms.com',
        'name' => 'Admin',
    ]);
    $user->assignRole($role);

    AccessKey::factory()->create(['access_key' => 'ABCD-1234']);
    AccessKey::factory()->create(['access_key' => 'EFGH-5678']);
});

describe('V2 Access Keys API', function () {

    it('includes api_version v2 in response meta', function () {
        $response = $this->asAdmin()->getJson('/api/v2/access-keys');

        $response->assertStatus(200)
            ->assertJsonPath('meta.api_version', 'v2');
    });

    it('can get all access keys', function () {
        assertV2CrudIndex('/api/v2/access-keys', 2, ['id', 'access_key', 'ip_address']);
    });

    it('can get a specific access key', function () {
        assertV2CrudShow(
            '/api/v2/access-keys/'.AccessKey::first()->id,
            ['id', 'access_key', 'ip_address', 'is_remote', 'is_satellite', 'is_prepaid',
                'created_at', 'updated_at']
        );
    });

    it('can create an access key', function () {
        assertV2CrudCreate('/api/v2/access-keys', [
            'access_key' => 'WXYZ-9999',
            'ip_address' => '192.168.1.100',
        ], AccessKey::class);
    });

    it('validates required fields on create', function () {
        $countBefore = AccessKey::count();
        $this->asAdmin()
            ->withHeaders(['Accept' => 'application/json'])
            ->post('/api/v2/access-keys', [])
            ->assertStatus(422);
        expect(AccessKey::count() - $countBefore)->toBe(0);
    });

    it('can update an access key', function () {
        assertV2CrudUpdate(
            '/api/v2/access-keys/'.AccessKey::first()->id,
            ['access_key' => 'NEWK-3333'],
            'access_key',
            'NEWK-3333'
        );
    });

    it('can delete an access key with 204 No Content', function () {
        assertV2CrudDelete(
            '/api/v2/access-keys/'.AccessKey::latest('id')->first()->id,
            AccessKey::class
        );
    });
});
