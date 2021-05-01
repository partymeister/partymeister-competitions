<?php

namespace Partymeister\Competitions\Console\Commands;

use Faker\Factory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Motor\Backend\Models\User;
use Partymeister\Competitions\Models\Competition;
use Partymeister\Competitions\Models\Entry;

/**
 * Class PartymeisterCompetitionsGenerateEntryCommand
 *
 * @package Partymeister\Competitions\Console\Commands
 */
class PartymeisterCompetitionsGenerateEntryCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'partymeister:competitions:generate:entry {competition_id} {count=1} {locale=en_US}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a competition with an optional name';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Auth::login(User::first());

        $faker = Factory::create($this->argument('locale'));

        $count = $this->argument('count');

        $competition = Competition::find($this->argument('competition_id'));
        if (is_null($competition)) {
            $this->error('Competition with ID '.$this->argument('competition_id').' not found!');

            return;
        }

        $options = [];
        foreach ($competition->option_groups as $optionGroup) {
            foreach ($optionGroup->options as $option) {
                $options[$option->id] = $option->name;
            }
        }

        for ($i = 0; $i < $count; $i++) {
            $entry = new Entry();
            $entry->competition_id = $competition->id;
            $entry->title = $faker->catchPhrase;
            $entry->author = $faker->name;
            $entry->filesize = $faker->numberBetween(100000, 9000000);
            $entry->platform = $faker->word;
            $entry->sort_position = $faker->numberBetween(1, 25);
            $entry->description = $faker->paragraph;
            $entry->organizer_description = $faker->paragraph;
            $entry->running_time = $faker->time();
            $entry->custom_option = $faker->word;
            $entry->ip_address = $faker->ipv4;
            $entry->allow_release = true;
            $entry->is_remote = rand(0, 1);
            $entry->is_recorded = rand(0, 1);
            $entry->upload_enabled = false;
            $entry->is_prepared = true;
            $entry->status = rand(1, 4);
            $entry->author_name = $faker->name;
            $entry->author_email = $faker->email;
            $entry->author_phone = $faker->phoneNumber;
            $entry->author_address = $faker->streetName;
            $entry->author_zip = $faker->postcode;
            $entry->author_city = $faker->city;
            $entry->author_country_iso_3166_1 = $faker->countryCode;
            $entry->composer_name = $faker->name;
            $entry->composer_email = $faker->email;
            $entry->composer_phone = $faker->phoneNumber;
            $entry->composer_address = $faker->streetName;
            $entry->composer_zip = $faker->postcode;
            $entry->composer_city = $faker->city;
            $entry->composer_country_iso_3166_1 = $faker->countryCode;
            $entry->composer_not_member_of_copyright_collective = rand(0, 1);

            $entry->save();

            if (count($options) > 0) {
                $option1 = array_rand($options);
                $option2 = array_rand($options);
                $option3 = array_rand($options);

                $entry->options()
                      ->attach($option1);
                $entry->options()
                      ->attach($option2);
                $entry->options()
                      ->attach($option3);
            }

            $this->info('Created entry: '.$entry->title.' for competition: '.$competition->name);
        }
    }
}
