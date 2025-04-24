<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Partymeister\Competitions\Models\CompetitionPrize;

/**
 * Class PartymeisterCompetitionsBackendCompetitionPrizeTest
 */
class PartymeisterCompetitionsBackendCompetitionPrizeTest extends TestCase
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
        'competition_prizes',
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

        $this->readPermission = create_test_permission_with_name('competition_prizes.read');
        $this->writePermission = create_test_permission_with_name('competition_prizes.write');
        $this->deletePermission = create_test_permission_with_name('competition_prizes.delete');

        $this->actingAs($this->user);
    }

    /** @test */
    public function can_see_grid_without_competition_prize()
    {
        $this->visit('/backend/competition_prizes')
            ->see(trans('partymeister-competitions::backend/competition_prizes.competition_prizes'))
            ->see(trans('motor-backend::backend/global.no_records'));
    }

    /** @test */
    public function can_see_grid_with_one_competition_prize()
    {
        $record = create_test_competition_prize();
        $this->visit('/backend/competition_prizes')
            ->see(trans('partymeister-competitions::backend/competition_prizes.competition_prizes'))
            ->see($record->name);
    }

    /** @test */
    public function can_visit_the_edit_form_of_a_competition_prize_and_use_the_back_button()
    {
        $record = create_test_competition_prize();
        $this->visit('/backend/competition_prizes')
            ->within('table', function () {
                $this->click(trans('motor-backend::backend/global.edit'));
            })
            ->seePageIs('/backend/competition_prizes/'.$record->id.'/edit')
            ->click(trans('motor-backend::backend/global.back'))
            ->seePageIs('/backend/competition_prizes');
    }

    /** @test */
    public function can_visit_the_edit_form_of_a_competition_prize_and_change_values()
    {
        $record = create_test_competition_prize();

        $this->visit('/backend/competition_prizes/'.$record->id.'/edit')
            ->see($record->name)
            ->type('Updated Competition prize', 'name')
            ->within('.box-footer', function () {
                $this->press(trans('partymeister-competitions::backend/competition_prizes.save'));
            })
            ->see(trans('partymeister-competitions::backend/competition_prizes.updated'))
            ->see('Updated Competition prize')
            ->seePageIs('/backend/competition_prizes');

        $record = CompetitionPrize::find($record->id);
        $this->assertEquals('Updated Competition prize', $record->name);
    }

    /** @test */
    public function can_click_the_competition_prize_create_button()
    {
        $this->visit('/backend/competition_prizes')
            ->click(trans('partymeister-competitions::backend/competition_prizes.new'))
            ->seePageIs('/backend/competition_prizes/create');
    }

    /** @test */
    public function can_create_a_new_competition_prize()
    {
        $this->visit('/backend/competition_prizes/create')
            ->see(trans('partymeister-competitions::backend/competition_prizes.new'))
            ->type('Create Competition prize Name', 'name')
            ->within('.box-footer', function () {
                $this->press(trans('partymeister-competitions::backend/competition_prizes.save'));
            })
            ->see(trans('partymeister-competitions::backend/competition_prizes.created'))
            ->see('Create Competition prize Name')
            ->seePageIs('/backend/competition_prizes');
    }

    /** @test */
    public function cannot_create_a_new_competition_prize_with_empty_fields()
    {
        $this->visit('/backend/competition_prizes/create')
            ->see(trans('partymeister-competitions::backend/competition_prizes.new'))
            ->within('.box-footer', function () {
                $this->press(trans('partymeister-competitions::backend/competition_prizes.save'));
            })
            ->see('Data missing!')
            ->seePageIs('/backend/competition_prizes/create');
    }

    /** @test */
    public function can_modify_a_competition_prize()
    {
        $record = create_test_competition_prize();
        $this->visit('/backend/competition_prizes/'.$record->id.'/edit')
            ->see(trans('partymeister-competitions::backend/competition_prizes.edit'))
            ->type('Modified Competition prize Name', 'name')
            ->within('.box-footer', function () {
                $this->press(trans('partymeister-competitions::backend/competition_prizes.save'));
            })
            ->see(trans('partymeister-competitions::backend/competition_prizes.updated'))
            ->see('Modified Competition prize Name')
            ->seePageIs('/backend/competition_prizes');
    }

    /** @test */
    public function can_delete_a_competition_prize()
    {
        create_test_competition_prize();

        $this->assertCount(1, CompetitionPrize::all());

        $this->visit('/backend/competition_prizes')
            ->within('table', function () {
                $this->press(trans('motor-backend::backend/global.delete'));
            })
            ->seePageIs('/backend/competition_prizes')
            ->see(trans('partymeister-competitions::backend/competition_prizes.deleted'));

        $this->assertCount(0, CompetitionPrize::all());
    }

    /** @test */
    public function can_paginate_competition_prize_results()
    {
        $records = create_test_competition_prize(100);
        $this->visit('/backend/competition_prizes')
            ->within('.pagination', function () {
                $this->click('3');
            })
            ->seePageIs('/backend/competition_prizes?page=3');
    }

    /** @test */
    public function can_search_competition_prize_results()
    {
        $records = create_test_competition_prize(10);
        $this->visit('/backend/competition_prizes')
            ->type(substr($records[6]->name, 0, 3), 'search')
            ->press('grid-search-button')
            ->seeInField('search', substr($records[6]->name, 0, 3))
            ->see($records[6]->name);
    }
}
