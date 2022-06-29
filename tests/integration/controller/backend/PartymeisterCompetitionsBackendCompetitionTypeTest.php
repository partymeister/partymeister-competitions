<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Partymeister\Competitions\Models\CompetitionType;

/**
 * Class PartymeisterCompetitionsBackendCompetitionTypeTest
 */
class PartymeisterCompetitionsBackendCompetitionTypeTest extends TestCase
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
        'competition_types',
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

        $this->readPermission = create_test_permission_with_name('competition_types.read');
        $this->writePermission = create_test_permission_with_name('competition_types.write');
        $this->deletePermission = create_test_permission_with_name('competition_types.delete');

        $this->actingAs($this->user);
    }

    /** @test */
    public function can_see_grid_without_competition_type()
    {
        $this->visit('/backend/competition_types')
            ->see(trans('partymeister-competitions::backend/competition_types.competition_types'))
            ->see(trans('motor-backend::backend/global.no_records'));
    }

    /** @test */
    public function can_see_grid_with_one_competition_type()
    {
        $record = create_test_competition_type();
        $this->visit('/backend/competition_types')
            ->see(trans('partymeister-competitions::backend/competition_types.competition_types'))
            ->see($record->name);
    }

    /** @test */
    public function can_visit_the_edit_form_of_a_competition_type_and_use_the_back_button()
    {
        $record = create_test_competition_type();
        $this->visit('/backend/competition_types')
            ->within('table', function () {
                $this->click(trans('motor-backend::backend/global.edit'));
            })
            ->seePageIs('/backend/competition_types/'.$record->id.'/edit')
            ->click(trans('motor-backend::backend/global.back'))
            ->seePageIs('/backend/competition_types');
    }

    /** @test */
    public function can_visit_the_edit_form_of_a_competition_type_and_change_values()
    {
        $record = create_test_competition_type();

        $this->visit('/backend/competition_types/'.$record->id.'/edit')
            ->see($record->name)
            ->type('Updated Competition type', 'name')
            ->within('.box-footer', function () {
                $this->press(trans('partymeister-competitions::backend/competition_types.save'));
            })
            ->see(trans('partymeister-competitions::backend/competition_types.updated'))
            ->see('Updated Competition type')
            ->seePageIs('/backend/competition_types');

        $record = CompetitionType::find($record->id);
        $this->assertEquals('Updated Competition type', $record->name);
    }

    /** @test */
    public function can_click_the_competition_type_create_button()
    {
        $this->visit('/backend/competition_types')
            ->click(trans('partymeister-competitions::backend/competition_types.new'))
            ->seePageIs('/backend/competition_types/create');
    }

    /** @test */
    public function can_create_a_new_competition_type()
    {
        $this->visit('/backend/competition_types/create')
            ->see(trans('partymeister-competitions::backend/competition_types.new'))
            ->type('Create Competition type Name', 'name')
            ->within('.box-footer', function () {
                $this->press(trans('partymeister-competitions::backend/competition_types.save'));
            })
            ->see(trans('partymeister-competitions::backend/competition_types.created'))
            ->see('Create Competition type Name')
            ->seePageIs('/backend/competition_types');
    }

    /** @test */
    public function cannot_create_a_new_competition_type_with_empty_fields()
    {
        $this->visit('/backend/competition_types/create')
            ->see(trans('partymeister-competitions::backend/competition_types.new'))
            ->within('.box-footer', function () {
                $this->press(trans('partymeister-competitions::backend/competition_types.save'));
            })
            ->see('Data missing!')
            ->seePageIs('/backend/competition_types/create');
    }

    /** @test */
    public function can_modify_a_competition_type()
    {
        $record = create_test_competition_type();
        $this->visit('/backend/competition_types/'.$record->id.'/edit')
            ->see(trans('partymeister-competitions::backend/competition_types.edit'))
            ->type('Modified Competition type Name', 'name')
            ->within('.box-footer', function () {
                $this->press(trans('partymeister-competitions::backend/competition_types.save'));
            })
            ->see(trans('partymeister-competitions::backend/competition_types.updated'))
            ->see('Modified Competition type Name')
            ->seePageIs('/backend/competition_types');
    }

    /** @test */
    public function can_delete_a_competition_type()
    {
        create_test_competition_type();

        $this->assertCount(1, CompetitionType::all());

        $this->visit('/backend/competition_types')
            ->within('table', function () {
                $this->press(trans('motor-backend::backend/global.delete'));
            })
            ->seePageIs('/backend/competition_types')
            ->see(trans('partymeister-competitions::backend/competition_types.deleted'));

        $this->assertCount(0, CompetitionType::all());
    }

    /** @test */
    public function can_paginate_competition_type_results()
    {
        $records = create_test_competition_type(100);
        $this->visit('/backend/competition_types')
            ->within('.pagination', function () {
                $this->click('3');
            })
            ->seePageIs('/backend/competition_types?page=3');
    }

    /** @test */
    public function can_search_competition_type_results()
    {
        $records = create_test_competition_type(10);
        $this->visit('/backend/competition_types')
            ->type(substr($records[6]->name, 0, 3), 'search')
            ->press('grid-search-button')
            ->seeInField('search', substr($records[6]->name, 0, 3))
            ->see($records[6]->name);
    }
}
