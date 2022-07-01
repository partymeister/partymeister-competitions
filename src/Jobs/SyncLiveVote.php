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
use Partymeister\Competitions\Models\LiveVote;

/**
 * Class SyncLiveVote
 */
class SyncLiveVote implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var LiveVote
     */
    public $liveVote;

    /**
     * Create a new job instance.
     *
     * SyncLiveVote constructor.
     *
     * @param  LiveVote  $liveVote
     */
    public function __construct(LiveVote $liveVote)
    {
        $this->liveVote = $liveVote;
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

        $data = [
            'data' => [
                'entry_id'       => $this->liveVote->entry_id,
                'competition_id' => $this->liveVote->competition_id,
                'sort_position'  => $this->liveVote->sort_position,
            ],
        ];
        Log::channel('debug')
           ->info(serialize($data));

        $client = new Client([
            'verify' => false,
        ]);

        $request = new Request('POST', config('partymeister-competitions-sync.server').config('partymeister-competitions-sync.api.livevote'), ['content-type' => 'application/json'], json_encode($data));

        try {
            $client->send($request);
        } catch (Exception $e) {
            Log::warning($e->getMessage());
        }
    }
}
