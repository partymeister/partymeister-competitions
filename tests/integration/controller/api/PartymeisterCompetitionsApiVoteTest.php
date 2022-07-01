<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * Class PartymeisterCompetitionsApiVoteTest
 */
class PartymeisterCompetitionsApiVoteTest extends TestCase
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
        'votes',
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
        $this->readPermission = create_test_permission_with_name('votes.read');
        $this->writePermission = create_test_permission_with_name('votes.write');
        $this->deletePermission = create_test_permission_with_name('votes.delete');
    }

    /**
     * @test
     */
    public function returns_403_for_vote_if_not_authenticated()
    {
        $this->json('GET', '/api/votes/1')->seeStatusCode(401)->seeJson(['error' => 'Unauthenticated.']);
    }

    /** @test */
    public function returns_404_for_non_existing_vote_record()
    {
        $this->user->givePermissionTo($this->readPermission);
        $this->json('GET', '/api/votes/1?api_token='.$this->user->api_token)->seeStatusCode(404)->seeJson([
            'message' => 'Record not found',
        ]);
    }

    /** @test */
    public function fails_if_trying_to_create_vote_without_payload()
    {
        $this->user->givePermissionTo($this->writePermission);
        $this->json('POST', '/api/votes?api_token='.$this->user->api_token)->seeStatusCode(422)->seeJson([
            'name' => ['The name field is required.'],
        ]);
    }

    /** @test */
    public function fails_if_trying_to_create_vote_without_permission()
    {
        $this->json('POST', '/api/votes?api_token='.$this->user->api_token)->seeStatusCode(403)->seeJson([
            'error' => 'Access denied.',
        ]);
    }

    /** @test */
    public function can_create_a_new_vote()
    {
        $this->user->givePermissionTo($this->writePermission);
        $this->json('POST', '/api/votes?api_token='.$this->user->api_token, [
            'name' => 'Test Vote',
        ])->seeStatusCode(200)->seeJson([
            'name' => 'Test Vote',
        ]);
    }

    /** @test */
    public function can_show_a_single_vote()
    {
        $this->user->givePermissionTo($this->readPermission);
        $record = create_test_vote();
        $this->json(
            'GET',
            '/api/votes/'.$record->id.'?api_token='.$this->user->api_token
        )->seeStatusCode(200)->seeJson([
            'name' => $record->name,
        ]);
    }

    /** @test */
    public function fails_to_show_a_single_vote_without_permission()
    {
        $record = create_test_vote();
        $this->json(
            'GET',
            '/api/votes/'.$record->id.'?api_token='.$this->user->api_token
        )->seeStatusCode(403)->seeJson([
            'error' => 'Access denied.',
        ]);
    }

    /** @test */
    public function can_get_empty_result_when_trying_to_show_multiple_vote()
    {
        $this->user->givePermissionTo($this->readPermission);
        $this->json('GET', '/api/votes?api_token='.$this->user->api_token)->seeStatusCode(200)->seeJson([
            'total' => 0,
        ]);
    }

    /** @test */
    public function can_show_multiple_vote()
    {
        $this->user->givePermissionTo($this->readPermission);
        $records = create_test_vote(10);
        $this->json('GET', '/api/votes?api_token='.$this->user->api_token)->seeStatusCode(200)->seeJson([
            'name' => $records[0]->name,
        ]);
    }

    /** @test */
    public function can_search_for_a_vote()
    {
        $this->user->givePermissionTo($this->readPermission);
        $records = create_test_vote(10);
        $this->json(
            'GET',
            '/api/votes?api_token='.$this->user->api_token.'&search='.$records[2]->name
        )->seeStatusCode(200)->seeJson([
            'name' => $records[2]->name,
        ]);
    }

    /** @test */
    public function can_show_a_second_vote_results_page()
    {
        $this->user->givePermissionTo($this->readPermission);
        create_test_vote(50);
        $this->json(
            'GET',
            '/api/votes?api_token='.$this->user->api_token.'&page=2'
        )->seeStatusCode(200)->seeJson([
            'current_page' => 2,
        ]);
    }

    /** @test */
    public function fails_if_trying_to_update_nonexisting_vote()
    {
        $this->user->givePermissionTo($this->writePermission);
        $this->json('PATCH', '/api/votes/2?api_token='.$this->user->api_token)->seeStatusCode(404)->seeJson([
            'message' => 'Record not found',
        ]);
    }

    /** @test */
    public function fails_if_trying_to_modify_a_vote_without_payload()
    {
        $this->user->givePermissionTo($this->writePermission);
        $record = create_test_vote();
        $this->json(
            'PATCH',
            '/api/votes/'.$record->id.'?api_token='.$this->user->api_token
        )->seeStatusCode(422)->seeJson([
            'name' => ['The name field is required.'],
        ]);
    }

    /** @test */
    public function fails_if_trying_to_modify_a_vote_without_permission()
    {
        $record = create_test_vote();
        $this->json(
            'PATCH',
            '/api/votes/'.$record->id.'?api_token='.$this->user->api_token
        )->seeStatusCode(403)->seeJson([
            'error' => 'Access denied.',
        ]);
    }

    /** @test */
    public function can_modify_a_vote()
    {
        $this->user->givePermissionTo($this->writePermission);
        $record = create_test_vote();
        $this->json('PATCH', '/api/votes/'.$record->id.'?api_token='.$this->user->api_token, [
            'name' => 'Modified Vote',
        ])->seeStatusCode(200)->seeJson([
            'name' => 'Modified Vote',
        ]);
    }

    /** @test */
    public function fails_if_trying_to_delete_a_non_existing_vote()
    {
        $this->user->givePermissionTo($this->deletePermission);
        $this->json('DELETE', '/api/votes/1?api_token='.$this->user->api_token)->seeStatusCode(404)->seeJson([
            'message' => 'Record not found',
        ]);
    }

    /** @test */
    public function fails_to_delete_a_vote_without_permission()
    {
        $record = create_test_vote();
        $this->json(
            'DELETE',
            '/api/votes/'.$record->id.'?api_token='.$this->user->api_token
        )->seeStatusCode(403)->seeJson([
            'error' => 'Access denied.',
        ]);
    }

    /** @test */
    public function can_delete_a_vote()
    {
        $this->user->givePermissionTo($this->deletePermission);
        $record = create_test_vote();
        $this->json(
            'DELETE',
            '/api/votes/'.$record->id.'?api_token='.$this->user->api_token
        )->seeStatusCode(200)->seeJson([
            'success' => true,
        ]);
    }
}
