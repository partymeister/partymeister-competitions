<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Partymeister\Competitions\Models\VoteCategory;

/**
 * Class PartymeisterCompetitionsBackendVoteCategoryTest
 */
class PartymeisterCompetitionsBackendVoteCategoryTest extends TestCase
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
        'vote_categories',
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

        $this->readPermission = create_test_permission_with_name('vote_categories.read');
        $this->writePermission = create_test_permission_with_name('vote_categories.write');
        $this->deletePermission = create_test_permission_with_name('vote_categories.delete');

        $this->actingAs($this->user);
    }

    /** @test */
    public function can_see_grid_without_vote_category()
    {
        $this->visit('/backend/vote_categories')
            ->see(trans('partymeister-competitions::backend/vote_categories.vote_categories'))
            ->see(trans('motor-backend::backend/global.no_records'));
    }

    /** @test */
    public function can_see_grid_with_one_vote_category()
    {
        $record = create_test_vote_category();
        $this->visit('/backend/vote_categories')
            ->see(trans('partymeister-competitions::backend/vote_categories.vote_categories'))
            ->see($record->name);
    }

    /** @test */
    public function can_visit_the_edit_form_of_a_vote_category_and_use_the_back_button()
    {
        $record = create_test_vote_category();
        $this->visit('/backend/vote_categories')
            ->within('table', function () {
                $this->click(trans('motor-backend::backend/global.edit'));
            })
            ->seePageIs('/backend/vote_categories/'.$record->id.'/edit')
            ->click(trans('motor-backend::backend/global.back'))
            ->seePageIs('/backend/vote_categories');
    }

    /** @test */
    public function can_visit_the_edit_form_of_a_vote_category_and_change_values()
    {
        $record = create_test_vote_category();

        $this->visit('/backend/vote_categories/'.$record->id.'/edit')
            ->see($record->name)
            ->type('Updated Vote category', 'name')
            ->within('.box-footer', function () {
                $this->press(trans('partymeister-competitions::backend/vote_categories.save'));
            })
            ->see(trans('partymeister-competitions::backend/vote_categories.updated'))
            ->see('Updated Vote category')
            ->seePageIs('/backend/vote_categories');

        $record = VoteCategory::find($record->id);
        $this->assertEquals('Updated Vote category', $record->name);
    }

    /** @test */
    public function can_click_the_vote_category_create_button()
    {
        $this->visit('/backend/vote_categories')
            ->click(trans('partymeister-competitions::backend/vote_categories.new'))
            ->seePageIs('/backend/vote_categories/create');
    }

    /** @test */
    public function can_create_a_new_vote_category()
    {
        $this->visit('/backend/vote_categories/create')
            ->see(trans('partymeister-competitions::backend/vote_categories.new'))
            ->type('Create Vote category Name', 'name')
            ->within('.box-footer', function () {
                $this->press(trans('partymeister-competitions::backend/vote_categories.save'));
            })
            ->see(trans('partymeister-competitions::backend/vote_categories.created'))
            ->see('Create Vote category Name')
            ->seePageIs('/backend/vote_categories');
    }

    /** @test */
    public function cannot_create_a_new_vote_category_with_empty_fields()
    {
        $this->visit('/backend/vote_categories/create')
            ->see(trans('partymeister-competitions::backend/vote_categories.new'))
            ->within('.box-footer', function () {
                $this->press(trans('partymeister-competitions::backend/vote_categories.save'));
            })
            ->see('Data missing!')
            ->seePageIs('/backend/vote_categories/create');
    }

    /** @test */
    public function can_modify_a_vote_category()
    {
        $record = create_test_vote_category();
        $this->visit('/backend/vote_categories/'.$record->id.'/edit')
            ->see(trans('partymeister-competitions::backend/vote_categories.edit'))
            ->type('Modified Vote category Name', 'name')
            ->within('.box-footer', function () {
                $this->press(trans('partymeister-competitions::backend/vote_categories.save'));
            })
            ->see(trans('partymeister-competitions::backend/vote_categories.updated'))
            ->see('Modified Vote category Name')
            ->seePageIs('/backend/vote_categories');
    }

    /** @test */
    public function can_delete_a_vote_category()
    {
        create_test_vote_category();

        $this->assertCount(1, VoteCategory::all());

        $this->visit('/backend/vote_categories')
            ->within('table', function () {
                $this->press(trans('motor-backend::backend/global.delete'));
            })
            ->seePageIs('/backend/vote_categories')
            ->see(trans('partymeister-competitions::backend/vote_categories.deleted'));

        $this->assertCount(0, VoteCategory::all());
    }

    /** @test */
    public function can_paginate_vote_category_results()
    {
        $records = create_test_vote_category(100);
        $this->visit('/backend/vote_categories')
            ->within('.pagination', function () {
                $this->click('3');
            })
            ->seePageIs('/backend/vote_categories?page=3');
    }

    /** @test */
    public function can_search_vote_category_results()
    {
        $records = create_test_vote_category(10);
        $this->visit('/backend/vote_categories')
            ->type(substr($records[6]->name, 0, 3), 'search')
            ->press('grid-search-button')
            ->seeInField('search', substr($records[6]->name, 0, 3))
            ->see($records[6]->name);
    }
}
