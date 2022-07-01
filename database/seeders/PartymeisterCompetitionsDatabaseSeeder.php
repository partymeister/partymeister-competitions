<?php

namespace Partymeister\Competitions\Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Class AccountsTableSeeder
 */
class PartymeisterCompetitionsDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(
            [
                OptionGroupsTableSeeder::class,
                VoteCategoriesTableSeeder::class,
                CompetitionTypesTableSeeder::class,
                CompetitionsTableSeeder::class,
                EntriesTableSeeder::class,
            ]
        );
    }
}
