<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Partymeister\Competitions\Models\AccessKey;

/**
 * Class PartymeisterCompetitionsBackendAccessKeyTest
 */
class PartymeisterCompetitionsBackendAccessKeyTest extends TestCase
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
        'access_keys',
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

        $this->readPermission = create_test_permission_with_name('access_keys.read');
        $this->writePermission = create_test_permission_with_name('access_keys.write');
        $this->deletePermission = create_test_permission_with_name('access_keys.delete');

        $this->actingAs($this->user);
    }

    /** @test */
    public function can_see_grid_without_access_key()
    {
        $this->visit('/backend/access_keys')
            ->see(trans('partymeister-competitions::backend/access_keys.access_keys'))
            ->see(trans('motor-backend::backend/global.no_records'));
    }

    /** @test */
    public function can_see_grid_with_one_access_key()
    {
        $record = create_test_access_key();
        $this->visit('/backend/access_keys')
            ->see(trans('partymeister-competitions::backend/access_keys.access_keys'))
            ->see($record->name);
    }

    /** @test */
    public function can_visit_the_edit_form_of_a_access_key_and_use_the_back_button()
    {
        $record = create_test_access_key();
        $this->visit('/backend/access_keys')
            ->within('table', function () {
                $this->click(trans('motor-backend::backend/global.edit'));
            })
            ->seePageIs('/backend/access_keys/'.$record->id.'/edit')
            ->click(trans('motor-backend::backend/global.back'))
            ->seePageIs('/backend/access_keys');
    }

    /** @test */
    public function can_visit_the_edit_form_of_a_access_key_and_change_values()
    {
        $record = create_test_access_key();

        $this->visit('/backend/access_keys/'.$record->id.'/edit')
            ->see($record->name)
            ->type('Updated Access key', 'name')
            ->within('.box-footer', function () {
                $this->press(trans('partymeister-competitions::backend/access_keys.save'));
            })
            ->see(trans('partymeister-competitions::backend/access_keys.updated'))
            ->see('Updated Access key')
            ->seePageIs('/backend/access_keys');

        $record = AccessKey::find($record->id);
        $this->assertEquals('Updated Access key', $record->name);
    }

    /** @test */
    public function can_click_the_access_key_create_button()
    {
        $this->visit('/backend/access_keys')
            ->click(trans('partymeister-competitions::backend/access_keys.new'))
            ->seePageIs('/backend/access_keys/create');
    }

    /** @test */
    public function can_create_a_new_access_key()
    {
        $this->visit('/backend/access_keys/create')
            ->see(trans('partymeister-competitions::backend/access_keys.new'))
            ->type('Create Access key Name', 'name')
            ->within('.box-footer', function () {
                $this->press(trans('partymeister-competitions::backend/access_keys.save'));
            })
            ->see(trans('partymeister-competitions::backend/access_keys.created'))
            ->see('Create Access key Name')
            ->seePageIs('/backend/access_keys');
    }

    /** @test */
    public function cannot_create_a_new_access_key_with_empty_fields()
    {
        $this->visit('/backend/access_keys/create')
            ->see(trans('partymeister-competitions::backend/access_keys.new'))
            ->within('.box-footer', function () {
                $this->press(trans('partymeister-competitions::backend/access_keys.save'));
            })
            ->see('Data missing!')
            ->seePageIs('/backend/access_keys/create');
    }

    /** @test */
    public function can_modify_a_access_key()
    {
        $record = create_test_access_key();
        $this->visit('/backend/access_keys/'.$record->id.'/edit')
            ->see(trans('partymeister-competitions::backend/access_keys.edit'))
            ->type('Modified Access key Name', 'name')
            ->within('.box-footer', function () {
                $this->press(trans('partymeister-competitions::backend/access_keys.save'));
            })
            ->see(trans('partymeister-competitions::backend/access_keys.updated'))
            ->see('Modified Access key Name')
            ->seePageIs('/backend/access_keys');
    }

    /** @test */
    public function can_delete_a_access_key()
    {
        create_test_access_key();

        $this->assertCount(1, AccessKey::all());

        $this->visit('/backend/access_keys')
            ->within('table', function () {
                $this->press(trans('motor-backend::backend/global.delete'));
            })
            ->seePageIs('/backend/access_keys')
            ->see(trans('partymeister-competitions::backend/access_keys.deleted'));

        $this->assertCount(0, AccessKey::all());
    }

    /** @test */
    public function can_paginate_access_key_results()
    {
        $records = create_test_access_key(100);
        $this->visit('/backend/access_keys')
            ->within('.pagination', function () {
                $this->click('3');
            })
            ->seePageIs('/backend/access_keys?page=3');
    }

    /** @test */
    public function can_search_access_key_results()
    {
        $records = create_test_access_key(10);
        $this->visit('/backend/access_keys')
            ->type(substr($records[6]->name, 0, 3), 'search')
            ->press('grid-search-button')
            ->seeInField('search', substr($records[6]->name, 0, 3))
            ->see($records[6]->name);
    }
}
