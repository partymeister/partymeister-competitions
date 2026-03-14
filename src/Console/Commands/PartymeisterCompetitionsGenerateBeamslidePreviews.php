<?php

namespace Partymeister\Competitions\Console\Commands;

use Illuminate\Console\Command;
use Partymeister\Competitions\Jobs\GenerateBeamslidePreview;
use Partymeister\Competitions\Models\Entry;
use Partymeister\Slides\Models\Slide;
use Partymeister\Slides\Models\SlideTemplate;

class PartymeisterCompetitionsGenerateBeamslidePreviews extends Command
{
    protected $signature = 'partymeister:competitions:generate-beamslide-previews {--force : Regenerate all previews}';

    protected $description = 'Generate beamslide preview images for competition entries';

    public function handle()
    {
        $template = SlideTemplate::where('template_for', 'competition')->first();
        if (! $template) {
            $this->error('No competition slide template found');
            return;
        }

        $entries = Entry::where('status', 1)->get();
        $count = 0;

        foreach ($entries as $entry) {
            // Skip if already has beamslide preview (unless --force)
            if (! $this->option('force') && $entry->getFirstMedia('beamslide')) {
                continue;
            }

            GenerateBeamslidePreview::dispatchSync($entry);
            $count++;
            $this->info("Queued beamslide preview for: {$entry->title}");
        }

        // Clean up old temp slides
        $oldTempSlides = Slide::where('name', 'like', '[TEMP]%')
            ->where('created_at', '<', now()->subMinutes(10))
            ->get();
        foreach ($oldTempSlides as $tempSlide) {
            $tempSlide->delete();
        }
        if ($oldTempSlides->count() > 0) {
            $this->info("Cleaned up {$oldTempSlides->count()} old temporary slides");
        }

        $this->info("Queued {$count} beamslide previews for generation");
    }
}
