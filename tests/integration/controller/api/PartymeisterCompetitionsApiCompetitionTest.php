<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * Class PartymeisterCompetitionsApiCompetitionTest
 */
class PartymeisterCompetitionsApiCompetitionTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @var
     */
    protected $user;

    /**
     * @var
     */
    protected $readPermission;

    /**
     * @var
     */
    protected $writePermission;

    /**
     * @var
     */
    protected $deletePermission;

    /**
     * @var array
     */
    protected $tables = [
        'competitions',
        'users',
        'languages',
        'clients',
        'permissions',
        'roles',
        'model_has_permissions',
        'model_has_roles',
        'role_has_permissions',
        'media',
    ];

    public function setUp()
    {
        parent::setUp();

        $this->withFactories(__DIR__.'/../../../../database/factories');

        $this->addDefaults();
    }

    protected function addDefaults()
    {
        $this->user = create_test_user();
        $this->readPermission = create_test_permission_with_name('competitions.read');
        $this->writePermission = create_test_permission_with_name('competitions.write');
        $this->deletePermission = create_test_permission_with_name('competitions.delete');
    }

    /**
     * @test
     */
    public function returns_403_for_competition_if_not_authenticated()
    {
        $this->json('GET', '/api/competitions/1')->seeStatusCode(401)->seeJson(['error' => 'Unauthenticated.']);
    }

    /** @test */
    public function returns_404_for_non_existing_competition_record()
    {
        $this->user->givePermissionTo($this->readPermission);
        $this->json('GET', '/api/competitions/1?api_token='.$this->user->api_token)->seeStatusCode(404)->seeJson([
            'message' => 'Record not found',
        ]);
    }

    /** @test */
    public function fails_if_trying_to_create_competition_without_payload()
    {
        $this->user->givePermissionTo($this->writePermission);
        $this->json('POST', '/api/competitions?api_token='.$this->user->api_token)->seeStatusCode(422)->seeJson([
            'name' => ['The name field is required.'],
        ]);
    }

    /** @test */
    public function fails_if_trying_to_create_competition_without_permission()
    {
        $this->json('POST', '/api/competitions?api_token='.$this->user->api_token)->seeStatusCode(403)->seeJson([
            'error' => 'Access denied.',
        ]);
    }

    /** @test */
    public function can_create_a_new_competition()
    {
        $this->user->givePermissionTo($this->writePermission);
        $this->json('POST', '/api/competitions?api_token='.$this->user->api_token, [
            'name' => 'Test Competition',
        ])->seeStatusCode(200)->seeJson([
            'name' => 'Test Competition',
        ]);
    }

    /** @test */
    public function can_show_a_single_competition()
    {
        $this->user->givePermissionTo($this->readPermission);
        $record = create_test_competition();
        $this->json(
            'GET',
            '/api/competitions/'.$record->id.'?api_token='.$this->user->api_token
        )->seeStatusCode(200)->seeJson([
            'name' => $record->name,
        ]);
    }

    /** @test */
    public function fails_to_show_a_single_competition_without_permission()
    {
        $record = create_test_competition();
        $this->json(
            'GET',
            '/api/competitions/'.$record->id.'?api_token='.$this->user->api_token
        )->seeStatusCode(403)->seeJson([
            'error' => 'Access denied.',
        ]);
    }

    /** @test */
    public function can_get_empty_result_when_trying_to_show_multiple_competition()
    {
        $this->user->givePermissionTo($this->readPermission);
        $this->json('GET', '/api/competitions?api_token='.$this->user->api_token)->seeStatusCode(200)->seeJson([
            'total' => 0,
        ]);
    }

    /** @test */
    public function can_show_multiple_competition()
    {
        $this->user->givePermissionTo($this->readPermission);
        $records = create_test_competition(10);
        $this->json('GET', '/api/competitions?api_token='.$this->user->api_token)->seeStatusCode(200)->seeJson([
            'name' => $records[0]->name,
        ]);
    }

    /** @test */
    public function can_search_for_a_competition()
    {
        $this->user->givePermissionTo($this->readPermission);
        $records = create_test_competition(10);
        $this->json(
            'GET',
            '/api/competitions?api_token='.$this->user->api_token.'&search='.$records[2]->name
        )->seeStatusCode(200)->seeJson([
            'name' => $records[2]->name,
        ]);
    }

    /** @test */
    public function can_show_a_second_competition_results_page()
    {
        $this->user->givePermissionTo($this->readPermission);
        create_test_competition(50);
        $this->json(
            'GET',
            '/api/competitions?api_token='.$this->user->api_token.'&page=2'
        )->seeStatusCode(200)->seeJson([
            'current_page' => 2,
        ]);
    }

    /** @test */
    public function fails_if_trying_to_update_nonexisting_competition()
    {
        $this->user->givePermissionTo($this->writePermission);
        $this->json('PATCH', '/api/competitions/2?api_token='.$this->user->api_token)->seeStatusCode(404)->seeJson([
            'message' => 'Record not found',
        ]);
    }

    /** @test */
    public function fails_if_trying_to_modify_a_competition_without_payload()
    {
        $this->user->givePermissionTo($this->writePermission);
        $record = create_test_competition();
        $this->json(
            'PATCH',
            '/api/competitions/'.$record->id.'?api_token='.$this->user->api_token
        )->seeStatusCode(422)->seeJson([
            'name' => ['The name field is required.'],
        ]);
    }

    /** @test */
    public function fails_if_trying_to_modify_a_competition_without_permission()
    {
        $record = create_test_competition();
        $this->json(
            'PATCH',
            '/api/competitions/'.$record->id.'?api_token='.$this->user->api_token
        )->seeStatusCode(403)->seeJson([
            'error' => 'Access denied.',
        ]);
    }

    /** @test */
    public function can_modify_a_competition()
    {
        $this->user->givePermissionTo($this->writePermission);
        $record = create_test_competition();
        $this->json('PATCH', '/api/competitions/'.$record->id.'?api_token='.$this->user->api_token, [
            'name' => 'Modified Competition',
        ])->seeStatusCode(200)->seeJson([
            'name' => 'Modified Competition',
        ]);
    }

    /** @test */
    public function fails_if_trying_to_delete_a_non_existing_competition()
    {
        $this->user->givePermissionTo($this->deletePermission);
        $this->json('DELETE', '/api/competitions/1?api_token='.$this->user->api_token)->seeStatusCode(404)->seeJson([
            'message' => 'Record not found',
        ]);
    }

    /** @test */
    public function fails_to_delete_a_competition_without_permission()
    {
        $record = create_test_competition();
        $this->json(
            'DELETE',
            '/api/competitions/'.$record->id.'?api_token='.$this->user->api_token
        )->seeStatusCode(403)->seeJson([
            'error' => 'Access denied.',
        ]);
    }

    /** @test */
    public function can_delete_a_competition()
    {
        $this->user->givePermissionTo($this->deletePermission);
        $record = create_test_competition();
        $this->json(
            'DELETE',
            '/api/competitions/'.$record->id.'?api_token='.$this->user->api_token
        )->seeStatusCode(200)->seeJson([
            'success' => true,
        ]);
    }
}
