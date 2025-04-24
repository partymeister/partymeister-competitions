<?php

namespace Partymeister\Competitions\Jobs;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Partymeister\Competitions\Http\Resources\CompetitionResource;
use Partymeister\Competitions\Models\Competition;

/**
 * Class SyncCompetition
 */
class SyncCompetition implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Competition
     */
    public $competition;

    /**
     * Create a new job instance.
     *
     * SyncCompetition constructor.
     */
    public function __construct(Competition $competition)
    {
        $this->competition = $competition;
    }

    /**
     * Execute the job.
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function handle()
    {
        if (! config('partymeister-competitions-sync.active')) {
            return;
        }

        $data = (new CompetitionResource($this->competition->load('qualified_entries')))->toArrayRecursive();

        $client = new Client([
            'verify' => false,
        ]);

        $request = new Request('POST', config('partymeister-competitions-sync.server').config('partymeister-competitions-sync.api.competition'), ['content-type' => 'application/json'], $data);

        try {
            $client->send($request);
        } catch (Exception $e) {
            Log::warning($e->getMessage());
        }
    }
}
