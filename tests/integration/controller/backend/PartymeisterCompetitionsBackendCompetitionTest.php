<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Partymeister\Competitions\Models\Competition;

/**
 * Class PartymeisterCompetitionsBackendCompetitionTest
 */
class PartymeisterCompetitionsBackendCompetitionTest extends TestCase
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
        $this->user = create_test_superadmin();

        $this->readPermission = create_test_permission_with_name('competitions.read');
        $this->writePermission = create_test_permission_with_name('competitions.write');
        $this->deletePermission = create_test_permission_with_name('competitions.delete');

        $this->actingAs($this->user);
    }

    /** @test */
    public function can_see_grid_without_competition()
    {
        $this->visit('/backend/competitions')
            ->see(trans('partymeister-competitions::backend/competitions.competitions'))
            ->see(trans('motor-backend::backend/global.no_records'));
    }

    /** @test */
    public function can_see_grid_with_one_competition()
    {
        $record = create_test_competition();
        $this->visit('/backend/competitions')
            ->see(trans('partymeister-competitions::backend/competitions.competitions'))
            ->see($record->name);
    }

    /** @test */
    public function can_visit_the_edit_form_of_a_competition_and_use_the_back_button()
    {
        $record = create_test_competition();
        $this->visit('/backend/competitions')
            ->within('table', function () {
                $this->click(trans('motor-backend::backend/global.edit'));
            })
            ->seePageIs('/backend/competitions/'.$record->id.'/edit')
            ->click(trans('motor-backend::backend/global.back'))
            ->seePageIs('/backend/competitions');
    }

    /** @test */
    public function can_visit_the_edit_form_of_a_competition_and_change_values()
    {
        $record = create_test_competition();

        $this->visit('/backend/competitions/'.$record->id.'/edit')
            ->see($record->name)
            ->type('Updated Competition', 'name')
            ->within('.box-footer', function () {
                $this->press(trans('partymeister-competitions::backend/competitions.save'));
            })
            ->see(trans('partymeister-competitions::backend/competitions.updated'))
            ->see('Updated Competition')
            ->seePageIs('/backend/competitions');

        $record = Competition::find($record->id);
        $this->assertEquals('Updated Competition', $record->name);
    }

    /** @test */
    public function can_click_the_competition_create_button()
    {
        $this->visit('/backend/competitions')
            ->click(trans('partymeister-competitions::backend/competitions.new'))
            ->seePageIs('/backend/competitions/create');
    }

    /** @test */
    public function can_create_a_new_competition()
    {
        $this->visit('/backend/competitions/create')
            ->see(trans('partymeister-competitions::backend/competitions.new'))
            ->type('Create Competition Name', 'name')
            ->within('.box-footer', function () {
                $this->press(trans('partymeister-competitions::backend/competitions.save'));
            })
            ->see(trans('partymeister-competitions::backend/competitions.created'))
            ->see('Create Competition Name')
            ->seePageIs('/backend/competitions');
    }

    /** @test */
    public function cannot_create_a_new_competition_with_empty_fields()
    {
        $this->visit('/backend/competitions/create')
            ->see(trans('partymeister-competitions::backend/competitions.new'))
            ->within('.box-footer', function () {
                $this->press(trans('partymeister-competitions::backend/competitions.save'));
            })
            ->see('Data missing!')
            ->seePageIs('/backend/competitions/create');
    }

    /** @test */
    public function can_modify_a_competition()
    {
        $record = create_test_competition();
        $this->visit('/backend/competitions/'.$record->id.'/edit')
            ->see(trans('partymeister-competitions::backend/competitions.edit'))
            ->type('Modified Competition Name', 'name')
            ->within('.box-footer', function () {
                $this->press(trans('partymeister-competitions::backend/competitions.save'));
            })
            ->see(trans('partymeister-competitions::backend/competitions.updated'))
            ->see('Modified Competition Name')
            ->seePageIs('/backend/competitions');
    }

    /** @test */
    public function can_delete_a_competition()
    {
        create_test_competition();

        $this->assertCount(1, Competition::all());

        $this->visit('/backend/competitions')
            ->within('table', function () {
                $this->press(trans('motor-backend::backend/global.delete'));
            })
            ->seePageIs('/backend/competitions')
            ->see(trans('partymeister-competitions::backend/competitions.deleted'));

        $this->assertCount(0, Competition::all());
    }

    /** @test */
    public function can_paginate_competition_results()
    {
        $records = create_test_competition(100);
        $this->visit('/backend/competitions')
            ->within('.pagination', function () {
                $this->click('3');
            })
            ->seePageIs('/backend/competitions?page=3');
    }

    /** @test */
    public function can_search_competition_results()
    {
        $records = create_test_competition(10);
        $this->visit('/backend/competitions')
            ->type(substr($records[6]->name, 0, 3), 'search')
            ->press('grid-search-button')
            ->seeInField('search', substr($records[6]->name, 0, 3))
            ->see($records[6]->name);
    }
}
