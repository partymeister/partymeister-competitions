<?php

namespace Partymeister\Competitions\Database\Seeders;

use Illuminate\Database\Seeder;
use Partymeister\Competitions\Models\OptionGroup;
use Partymeister\Core\Models\User;
use Illuminate\Support\Facades\DB;

/**
 * Class AccountsTableSeeder
 * @package Partymeister\Accounting\Database\Seeds
 */
class VoteCategoriesTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('vote_categories')->insert([
            'name'       => 'Default',
            'points'     => 5,
            'created_by' => User::get()->first()->id,
            'updated_by' => User::get()->first()->id,
        ]);
    }
}
