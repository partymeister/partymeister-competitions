<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Partymeister\Competitions\Models\OptionGroup;

/**
 * Class PartymeisterCompetitionsBackendOptionGroupTest
 */
class PartymeisterCompetitionsBackendOptionGroupTest extends TestCase
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
        'option_groups',
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

        $this->readPermission = create_test_permission_with_name('option_groups.read');
        $this->writePermission = create_test_permission_with_name('option_groups.write');
        $this->deletePermission = create_test_permission_with_name('option_groups.delete');

        $this->actingAs($this->user);
    }

    /** @test */
    public function can_see_grid_without_option_group()
    {
        $this->visit('/backend/option_groups')
            ->see(trans('partymeister-competitions::backend/option_groups.option_groups'))
            ->see(trans('motor-backend::backend/global.no_records'));
    }

    /** @test */
    public function can_see_grid_with_one_option_group()
    {
        $record = create_test_option_group();
        $this->visit('/backend/option_groups')
            ->see(trans('partymeister-competitions::backend/option_groups.option_groups'))
            ->see($record->name);
    }

    /** @test */
    public function can_visit_the_edit_form_of_a_option_group_and_use_the_back_button()
    {
        $record = create_test_option_group();
        $this->visit('/backend/option_groups')
            ->within('table', function () {
                $this->click(trans('motor-backend::backend/global.edit'));
            })
            ->seePageIs('/backend/option_groups/'.$record->id.'/edit')
            ->click(trans('motor-backend::backend/global.back'))
            ->seePageIs('/backend/option_groups');
    }

    /** @test */
    public function can_visit_the_edit_form_of_a_option_group_and_change_values()
    {
        $record = create_test_option_group();

        $this->visit('/backend/option_groups/'.$record->id.'/edit')
            ->see($record->name)
            ->type('Updated Option group', 'name')
            ->within('.box-footer', function () {
                $this->press(trans('partymeister-competitions::backend/option_groups.save'));
            })
            ->see(trans('partymeister-competitions::backend/option_groups.updated'))
            ->see('Updated Option group')
            ->seePageIs('/backend/option_groups');

        $record = OptionGroup::find($record->id);
        $this->assertEquals('Updated Option group', $record->name);
    }

    /** @test */
    public function can_click_the_option_group_create_button()
    {
        $this->visit('/backend/option_groups')
            ->click(trans('partymeister-competitions::backend/option_groups.new'))
            ->seePageIs('/backend/option_groups/create');
    }

    /** @test */
    public function can_create_a_new_option_group()
    {
        $this->visit('/backend/option_groups/create')
            ->see(trans('partymeister-competitions::backend/option_groups.new'))
            ->type('Create Option group Name', 'name')
            ->within('.box-footer', function () {
                $this->press(trans('partymeister-competitions::backend/option_groups.save'));
            })
            ->see(trans('partymeister-competitions::backend/option_groups.created'))
            ->see('Create Option group Name')
            ->seePageIs('/backend/option_groups');
    }

    /** @test */
    public function cannot_create_a_new_option_group_with_empty_fields()
    {
        $this->visit('/backend/option_groups/create')
            ->see(trans('partymeister-competitions::backend/option_groups.new'))
            ->within('.box-footer', function () {
                $this->press(trans('partymeister-competitions::backend/option_groups.save'));
            })
            ->see('Data missing!')
            ->seePageIs('/backend/option_groups/create');
    }

    /** @test */
    public function can_modify_a_option_group()
    {
        $record = create_test_option_group();
        $this->visit('/backend/option_groups/'.$record->id.'/edit')
            ->see(trans('partymeister-competitions::backend/option_groups.edit'))
            ->type('Modified Option group Name', 'name')
            ->within('.box-footer', function () {
                $this->press(trans('partymeister-competitions::backend/option_groups.save'));
            })
            ->see(trans('partymeister-competitions::backend/option_groups.updated'))
            ->see('Modified Option group Name')
            ->seePageIs('/backend/option_groups');
    }

    /** @test */
    public function can_delete_a_option_group()
    {
        create_test_option_group();

        $this->assertCount(1, OptionGroup::all());

        $this->visit('/backend/option_groups')
            ->within('table', function () {
                $this->press(trans('motor-backend::backend/global.delete'));
            })
            ->seePageIs('/backend/option_groups')
            ->see(trans('partymeister-competitions::backend/option_groups.deleted'));

        $this->assertCount(0, OptionGroup::all());
    }

    /** @test */
    public function can_paginate_option_group_results()
    {
        $records = create_test_option_group(100);
        $this->visit('/backend/option_groups')
            ->within('.pagination', function () {
                $this->click('3');
            })
            ->seePageIs('/backend/option_groups?page=3');
    }

    /** @test */
    public function can_search_option_group_results()
    {
        $records = create_test_option_group(10);
        $this->visit('/backend/option_groups')
            ->type(substr($records[6]->name, 0, 3), 'search')
            ->press('grid-search-button')
            ->seeInField('search', substr($records[6]->name, 0, 3))
            ->see($records[6]->name);
    }
}
