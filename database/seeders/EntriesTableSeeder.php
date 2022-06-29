<?php

namespace Partymeister\Competitions\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Partymeister\Competitions\Models\Competition;

/**
 * Class AccountsTableSeeder
 */
class EntriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $competition = Competition::first();
        Artisan::call('partymeister:competitions:generate:entry '.$competition->id.' 5');
    }
}
