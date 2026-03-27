<?php

namespace Partymeister\Competitions\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use League\Csv\Writer;

class PartymeisterCompetitionsExportShaderShowdownVotesCommand extends Command
{
    protected $signature = 'partymeister:competitions:export-shader-showdown-votes
                            {--filter= : Filter competitions by name (e.g. "Shader")}';

    protected $description = 'Export individual votes per visitor as CSV for the shader showdown';

    public function handle()
    {
        $filter = $this->option('filter');

        $query = DB::table('votes')
            ->join('entries', 'votes.entry_id', '=', 'entries.id')
            ->join('competitions', 'votes.competition_id', '=', 'competitions.id')
            ->select(
                'votes.visitor_id',
                'competitions.name as competition_name',
                'entries.title as entry_name',
                'votes.points',
            )
            ->where('entries.status', 1)
            ->orderBy('competitions.sort_position')
            ->orderBy('votes.visitor_id')
            ->orderBy('entries.sort_position');

        if ($filter) {
            $query->where('competitions.name', 'like', "%{$filter}%");
        }

        $rows = $query->get();

        $csv = Writer::createFromString();
        $csv->setEnclosure('"');
        $csv->setDelimiter(';');
        $csv->insertOne(['visitor_id', 'competition_name', 'entry_name', 'points']);

        foreach ($rows as $row) {
            $csv->insertOne([
                $row->visitor_id,
                $row->competition_name,
                $row->entry_name,
                $row->points,
            ]);
        }

        $filename = 'shader-showdown-votes.csv';
        file_put_contents($filename, $csv->toString());

        $this->info("Exported {$rows->count()} votes to {$filename}");
    }
}
