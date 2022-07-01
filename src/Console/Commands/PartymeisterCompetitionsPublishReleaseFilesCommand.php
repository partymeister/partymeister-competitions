<?php

namespace Partymeister\Competitions\Console\Commands;

use Illuminate\Console\Command;
use Partymeister\Competitions\Models\Competition;
use Partymeister\Competitions\Services\CompetitionService;

/**
 * Class PartymeisterCompetitionsPublishReleaseFilesCommand
 */
class PartymeisterCompetitionsPublishReleaseFilesCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'partymeister:competitions:publish-release-files';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make symlinks to all uploaded files';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        foreach (Competition::all() as $competition) {
            CompetitionService::hardLinkReleases($competition);
        }
    }

    /**
     * @param $directory
     */
    protected function mkdir($directory)
    {
        if (! is_dir($directory)) {
            mkdir($directory);
        }
    }
}
