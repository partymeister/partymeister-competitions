<?php

namespace Partymeister\Competitions\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Partymeister\Competitions\Models\Entry;
use Partymeister\Slides\Helpers\ScreenshotHelper;
use Partymeister\Slides\Models\Slide;
use Partymeister\Slides\Models\SlideTemplate;

class GenerateBeamslidePreview implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public Entry $entry
    ) {}

    public function handle(): void
    {
        $template = SlideTemplate::where('template_for', 'competition')->first();
        if (! $template) {
            return;
        }

        $entry = $this->entry->load('competition', 'options');

        // Build replacement data (queue-safe, no session/request dependency)
        $replacements = [
            'id'                     => $entry->id,
            'title'                  => $entry->title ?? ' ',
            'author'                 => $entry->author ?? ' ',
            'description'            => nl2br(e($entry->description ?: ' ')),
            'competition_name'       => $entry->competition->name ?? ' ',
            'sort_position'          => $entry->sort_position,
            'sort_position_prefixed' => str_pad($entry->sort_position, 2, '0', STR_PAD_LEFT),
            'filesize'               => (int) $entry->filesize,
            'filesize_bytes'         => (int) $entry->filesize,
            'filesize_human'         => $entry->filesize > 0 ? $this->bytesToHuman($entry->filesize) : ' ',
            'running_time'           => $entry->running_time ?? ' ',
            'custom_option'          => $entry->custom_option ?? ' ',
            'previous_sort_position' => ' ',
            'previous_author'        => ' ',
            'previous_title'         => ' ',
        ];

        // Add options
        foreach ($entry->options as $i => $option) {
            $replacements['option_'.($i + 1)] = $option->name;
        }

        // Replace placeholders: read from placeholder, write to content (mirrors useSlideReplacer.ts)
        $defsArray = json_decode($template->definitions, true);
        if (isset($defsArray['elements'])) {
            foreach ($defsArray['elements'] as &$element) {
                if (!isset($element['properties'])) continue;
                $props = &$element['properties'];
                $source = $props['placeholder'] ?? '';
                if ($source === '') continue;

                foreach ($replacements as $key => $value) {
                    if (is_string($value)) {
                        $source = str_replace('<<'.$key.'>>', $value, $source);
                    }
                }
                $source = preg_replace('/<<[^>]+>>/', '', $source);
                $props['content'] = $source;
            }
            unset($element);
        }
        $definitions = json_encode($defsArray);

        // Create temporary slide for the screenshot worker to render
        $s = new Slide();
        $s->name = '[TEMP] Beamslide preview: '.$entry->title;
        $s->slide_type = 'compo';
        $s->definitions = $definitions;
        $s->save();

        // Queue screenshot targeting the Entry model's beamslide collection
        $entry->clearMediaCollection('beamslide');
        $browser = new ScreenshotHelper();
        $browser->screenshot(
            config('app.url_internal').route('backend.slides.render', [$s->id], false),
            storage_path().'/beamslide_entry_'.$entry->id.'.png',
            $entry->id,
            Entry::class,
            'beamslide'
        );
    }

    private function bytesToHuman(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        return round($bytes, 2).' '.$units[$i];
    }
}
