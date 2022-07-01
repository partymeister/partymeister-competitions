<?php

namespace Partymeister\Competitions\Console\Commands;

use Faker\Factory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Motor\Admin\Models\User;
use Partymeister\Competitions\Models\Competition;
use Partymeister\Competitions\Models\CompetitionType;
use Partymeister\Competitions\Models\OptionGroup;
use Partymeister\Competitions\Models\VoteCategory;

/**
 * Class PartymeisterCompetitionsGenerateCompetitionCommand
 */
class PartymeisterCompetitionsGenerateCompetitionCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'partymeister:competitions:generate:competition {name=faker} {locale=en_US}';

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
        Auth::login(User::find(1));

        $faker = Factory::create($this->argument('locale'));

        $competition = new Competition();
        $competition->name = $this->argument('name') === 'faker' ? $faker->catchPhrase : $this->argument('name');
        $competition->sort_position = $faker->numberBetween(0, 99);
        $competition->prizegiving_sort_position = $faker->numberBetween(0, 99);
        $competition->competition_type_id = CompetitionType::inRandomOrder()
                                                           ->first()->id;
        $competition->has_prizegiving = true;
        $competition->upload_enabled = true;
        $competition->voting_enabled = false;

        $competition->save();
        $competition->option_groups()
                    ->attach(OptionGroup::inRandomOrder()
                                        ->first()->id);
        $competition->vote_categories()
                    ->attach(VoteCategory::first()->id);

        $this->info('Generated competition '.$competition->name);
    }
}
