<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Partymeister\Competitions\Models\Vote;

/**
 * Class PartymeisterCompetitionsBackendVoteTest
 */
class PartymeisterCompetitionsBackendVoteTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;

    protected $readPermission;

    protected $writePermission;

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

    protected function setUp()
    {
        parent::setUp();

        $this->withFactories(__DIR__.'/../../../../database/factories');

        $this->addDefaults();
    }

    protected function addDefaults()
    {
        $this->user = create_test_superadmin();

        $this->readPermission = create_test_permission_with_name('votes.read');
        $this->writePermission = create_test_permission_with_name('votes.write');
        $this->deletePermission = create_test_permission_with_name('votes.delete');

        $this->actingAs($this->user);
    }

    /** @test */
    public function can_see_grid_without_vote()
    {
        $this->visit('/backend/votes')
            ->see(trans('partymeister-competitions::backend/votes.votes'))
            ->see(trans('motor-backend::backend/global.no_records'));
    }

    /** @test */
    public function can_see_grid_with_one_vote()
    {
        $record = create_test_vote();
        $this->visit('/backend/votes')
            ->see(trans('partymeister-competitions::backend/votes.votes'))
            ->see($record->name);
    }

    /** @test */
    public function can_visit_the_edit_form_of_a_vote_and_use_the_back_button()
    {
        $record = create_test_vote();
        $this->visit('/backend/votes')
            ->within('table', function () {
                $this->click(trans('motor-backend::backend/global.edit'));
            })
            ->seePageIs('/backend/votes/'.$record->id.'/edit')
            ->click(trans('motor-backend::backend/global.back'))
            ->seePageIs('/backend/votes');
    }

    /** @test */
    public function can_visit_the_edit_form_of_a_vote_and_change_values()
    {
        $record = create_test_vote();

        $this->visit('/backend/votes/'.$record->id.'/edit')
            ->see($record->name)
            ->type('Updated Vote', 'name')
            ->within('.box-footer', function () {
                $this->press(trans('partymeister-competitions::backend/votes.save'));
            })
            ->see(trans('partymeister-competitions::backend/votes.updated'))
            ->see('Updated Vote')
            ->seePageIs('/backend/votes');

        $record = Vote::find($record->id);
        $this->assertEquals('Updated Vote', $record->name);
    }

    /** @test */
    public function can_click_the_vote_create_button()
    {
        $this->visit('/backend/votes')
            ->click(trans('partymeister-competitions::backend/votes.new'))
            ->seePageIs('/backend/votes/create');
    }

    /** @test */
    public function can_create_a_new_vote()
    {
        $this->visit('/backend/votes/create')
            ->see(trans('partymeister-competitions::backend/votes.new'))
            ->type('Create Vote Name', 'name')
            ->within('.box-footer', function () {
                $this->press(trans('partymeister-competitions::backend/votes.save'));
            })
            ->see(trans('partymeister-competitions::backend/votes.created'))
            ->see('Create Vote Name')
            ->seePageIs('/backend/votes');
    }

    /** @test */
    public function cannot_create_a_new_vote_with_empty_fields()
    {
        $this->visit('/backend/votes/create')
            ->see(trans('partymeister-competitions::backend/votes.new'))
            ->within('.box-footer', function () {
                $this->press(trans('partymeister-competitions::backend/votes.save'));
            })
            ->see('Data missing!')
            ->seePageIs('/backend/votes/create');
    }

    /** @test */
    public function can_modify_a_vote()
    {
        $record = create_test_vote();
        $this->visit('/backend/votes/'.$record->id.'/edit')
            ->see(trans('partymeister-competitions::backend/votes.edit'))
            ->type('Modified Vote Name', 'name')
            ->within('.box-footer', function () {
                $this->press(trans('partymeister-competitions::backend/votes.save'));
            })
            ->see(trans('partymeister-competitions::backend/votes.updated'))
            ->see('Modified Vote Name')
            ->seePageIs('/backend/votes');
    }

    /** @test */
    public function can_delete_a_vote()
    {
        create_test_vote();

        $this->assertCount(1, Vote::all());

        $this->visit('/backend/votes')
            ->within('table', function () {
                $this->press(trans('motor-backend::backend/global.delete'));
            })
            ->seePageIs('/backend/votes')
            ->see(trans('partymeister-competitions::backend/votes.deleted'));

        $this->assertCount(0, Vote::all());
    }

    /** @test */
    public function can_paginate_vote_results()
    {
        $records = create_test_vote(100);
        $this->visit('/backend/votes')
            ->within('.pagination', function () {
                $this->click('3');
            })
            ->seePageIs('/backend/votes?page=3');
    }

    /** @test */
    public function can_search_vote_results()
    {
        $records = create_test_vote(10);
        $this->visit('/backend/votes')
            ->type(substr($records[6]->name, 0, 3), 'search')
            ->press('grid-search-button')
            ->seeInField('search', substr($records[6]->name, 0, 3))
            ->see($records[6]->name);
    }
}
