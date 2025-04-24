<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Partymeister\Competitions\Models\Entry;

/**
 * Class PartymeisterCompetitionsBackendEntryTest
 */
class PartymeisterCompetitionsBackendEntryTest extends TestCase
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
        'entries',
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

        $this->readPermission = create_test_permission_with_name('entries.read');
        $this->writePermission = create_test_permission_with_name('entries.write');
        $this->deletePermission = create_test_permission_with_name('entries.delete');

        $this->actingAs($this->user);
    }

    /** @test */
    public function can_see_grid_without_entry()
    {
        $this->visit('/backend/entries')
            ->see(trans('partymeister-competitions::backend/entries.entries'))
            ->see(trans('motor-backend::backend/global.no_records'));
    }

    /** @test */
    public function can_see_grid_with_one_entry()
    {
        $record = create_test_entry();
        $this->visit('/backend/entries')
            ->see(trans('partymeister-competitions::backend/entries.entries'))
            ->see($record->name);
    }

    /** @test */
    public function can_visit_the_edit_form_of_a_entry_and_use_the_back_button()
    {
        $record = create_test_entry();
        $this->visit('/backend/entries')
            ->within('table', function () {
                $this->click(trans('motor-backend::backend/global.edit'));
            })
            ->seePageIs('/backend/entries/'.$record->id.'/edit')
            ->click(trans('motor-backend::backend/global.back'))
            ->seePageIs('/backend/entries');
    }

    /** @test */
    public function can_visit_the_edit_form_of_a_entry_and_change_values()
    {
        $record = create_test_entry();

        $this->visit('/backend/entries/'.$record->id.'/edit')
            ->see($record->name)
            ->type('Updated Entry', 'name')
            ->within('.box-footer', function () {
                $this->press(trans('partymeister-competitions::backend/entries.save'));
            })
            ->see(trans('partymeister-competitions::backend/entries.updated'))
            ->see('Updated Entry')
            ->seePageIs('/backend/entries');

        $record = Entry::find($record->id);
        $this->assertEquals('Updated Entry', $record->name);
    }

    /** @test */
    public function can_click_the_entry_create_button()
    {
        $this->visit('/backend/entries')
            ->click(trans('partymeister-competitions::backend/entries.new'))
            ->seePageIs('/backend/entries/create');
    }

    /** @test */
    public function can_create_a_new_entry()
    {
        $this->visit('/backend/entries/create')
            ->see(trans('partymeister-competitions::backend/entries.new'))
            ->type('Create Entry Name', 'name')
            ->within('.box-footer', function () {
                $this->press(trans('partymeister-competitions::backend/entries.save'));
            })
            ->see(trans('partymeister-competitions::backend/entries.created'))
            ->see('Create Entry Name')
            ->seePageIs('/backend/entries');
    }

    /** @test */
    public function cannot_create_a_new_entry_with_empty_fields()
    {
        $this->visit('/backend/entries/create')
            ->see(trans('partymeister-competitions::backend/entries.new'))
            ->within('.box-footer', function () {
                $this->press(trans('partymeister-competitions::backend/entries.save'));
            })
            ->see('Data missing!')
            ->seePageIs('/backend/entries/create');
    }

    /** @test */
    public function can_modify_a_entry()
    {
        $record = create_test_entry();
        $this->visit('/backend/entries/'.$record->id.'/edit')
            ->see(trans('partymeister-competitions::backend/entries.edit'))
            ->type('Modified Entry Name', 'name')
            ->within('.box-footer', function () {
                $this->press(trans('partymeister-competitions::backend/entries.save'));
            })
            ->see(trans('partymeister-competitions::backend/entries.updated'))
            ->see('Modified Entry Name')
            ->seePageIs('/backend/entries');
    }

    /** @test */
    public function can_delete_a_entry()
    {
        create_test_entry();

        $this->assertCount(1, Entry::all());

        $this->visit('/backend/entries')
            ->within('table', function () {
                $this->press(trans('motor-backend::backend/global.delete'));
            })
            ->seePageIs('/backend/entries')
            ->see(trans('partymeister-competitions::backend/entries.deleted'));

        $this->assertCount(0, Entry::all());
    }

    /** @test */
    public function can_paginate_entry_results()
    {
        $records = create_test_entry(100);
        $this->visit('/backend/entries')
            ->within('.pagination', function () {
                $this->click('3');
            })
            ->seePageIs('/backend/entries?page=3');
    }

    /** @test */
    public function can_search_entry_results()
    {
        $records = create_test_entry(10);
        $this->visit('/backend/entries')
            ->type(substr($records[6]->name, 0, 3), 'search')
            ->press('grid-search-button')
            ->seeInField('search', substr($records[6]->name, 0, 3))
            ->see($records[6]->name);
    }
}
