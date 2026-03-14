@if ($errors->any())
    <div class="rounded-lg border border-error/40 border-l-4 border-l-error bg-error/15 px-4 py-3 text-error mb-4">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </div>
@endif
<h3 class="mb-4">
    @if ($record->is_remote)
        <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium bg-error/15 text-error border border-error/40">REMOTE</span>
    @endif
    Entry detail for: {{$record->title}} by {{$record->author}}
</h3>

{{-- Entry info card --}}
<div class="rounded-lg bg-surface border border-border shadow-[0_4px_12px_rgba(0,0,0,0.4)] mb-4">
    <div class="px-5 py-3 bg-surface-raised rounded-t-lg text-heading font-semibold border-b border-border">
        <h4>Entry information</h4>
    </div>
    <div class="p-5">
        <dl class="grid grid-cols-1 md:grid-cols-[1fr_2fr] gap-y-3 gap-x-4">
            <dt class="font-semibold">Identifier</dt>
            <dd>{{ $record->identifier }}</dd>

            <dt class="font-semibold">{{trans('partymeister-competitions::backend/competitions.competition')}}</dt>
            <dd>{{ $record->competition->name }}</dd>

            @if($record->competition->competition_type->has_running_time)
                <dt class="font-semibold">{{trans('partymeister-competitions::backend/entries.running_time')}}</dt>
                <dd>{{ $record->running_time }}</dd>
            @endif

            <dt class="font-semibold">{{trans('partymeister-competitions::backend/entries.description')}}</dt>
            <dd>{!! nl2br(e($record->description)) !!}</dd>

            <dt class="font-semibold">{{trans('partymeister-competitions::backend/entries.organizer_description')}}</dt>
            <dd>{!! nl2br(e($record->organizer_description)) !!}</dd>

            <dt class="font-semibold">{{trans('partymeister-competitions::backend/entries.notify_about_status')}}</dt>
            <dd>{{$record->notify_about_status ? 'Yes' : 'No'}}</dd>

            @if ($record->discord_name !== '' && $record->discord_name !== null)
                <dt class="font-semibold">{{trans('partymeister-competitions::backend/entries.discord_name_short')}}</dt>
                <dd>{!! nl2br(e($record->discord_name)) !!}</dd>
            @endif

            @if ($record->representative !== '' && $record->representative !== null)
                <dt class="font-semibold">{{trans('partymeister-competitions::backend/entries.representative')}}</dt>
                <dd>{!! nl2br(e($record->representative)) !!}</dd>
            @endif
        </dl>
    </div>
</div>

{{-- Options & technical info card --}}
@if ($record->options->count() > 0 || $record->custom_option != '' || ($record->ai_data && $record->ai_usage != '') || ($record->engine_data && $record->engine_option != ''))
<div class="rounded-lg bg-surface border border-border shadow-[0_4px_12px_rgba(0,0,0,0.4)] mb-4">
    <div class="px-5 py-3 bg-surface-raised rounded-t-lg text-heading font-semibold border-b border-border">
        <h4>Options & technical details</h4>
    </div>
    <div class="p-5">
        <dl class="grid grid-cols-1 md:grid-cols-[1fr_2fr] gap-y-3 gap-x-4">
            @if ($record->options->count() > 0)
                <dt class="font-semibold">{{trans('partymeister-competitions::backend/entries.option_info')}}</dt>
                <dd>
                    <ul class="list-disc list-inside">
                        @foreach ($record->options as $option)
                            <li>{{$option->name}}</li>
                        @endforeach
                    </ul>
                </dd>
            @endif

            @if ($record->custom_option != '')
                <dt class="font-semibold">{{trans('partymeister-competitions::backend/entries.custom_option_short')}}</dt>
                <dd>{{ $record->custom_option }}</dd>
            @endif

            @if ($record->ai_data && $record->ai_usage != '')
                <dt class="font-semibold">{{trans('partymeister-competitions::backend/entries.ai_information')}}</dt>
                <dd>
                    <strong>{{ trans('partymeister-competitions::backend/entries.ai_usage') }}:</strong> {{ trans('partymeister-competitions::backend/entries.ai_usage_options.' . $record->ai_usage) }}<br>
                    {{ $record->ai_usage_description }}
                </dd>
            @endif

            @if ($record->engine_data && $record->engine_option != '')
                <dt class="font-semibold">{{trans('partymeister-competitions::backend/entries.engine_information')}}</dt>
                <dd>
                    <strong>{{ trans('partymeister-competitions::backend/entries.engine_option') }}:</strong> {{ trans('partymeister-competitions::backend/entries.engine_options.' . $record->engine_option) }}<br>
                    <strong>Engine:</strong> {{ $record->engine_option_description }}<br>
                    <strong>Creator involvement:</strong> {{ trans('partymeister-competitions::backend/entries.engine_creator_involvement_options.' . $record->engine_creator_involvement) }}
                </dd>
            @endif
        </dl>
    </div>
</div>
@endif

{{-- Screenshot & beamslide --}}
@if($record->getFirstMedia('screenshot') || (isset($beamslideUrl) && $beamslideUrl))
<div class="rounded-lg bg-surface border border-border shadow-[0_4px_12px_rgba(0,0,0,0.4)] mb-4">
    <div class="px-5 py-3 bg-surface-raised rounded-t-lg text-heading font-semibold border-b border-border">
        <h4>Preview</h4>
    </div>
    <div class="p-5 space-y-4">
        @if($record->getFirstMedia('screenshot'))
            <h5 class="mb-2">Screenshot</h5>
            <a data-caption="{{$record->title}} by {{$record->author}}" data-fancybox="gallery"
               href="{{$record->getFirstMedia('screenshot')->getUrl('preview')}}" class="hover:opacity-90 transition-opacity block">
                <img src="{{$record->getFirstMedia('screenshot')->getUrl('preview')}}" alt="Screenshot for {{ $record->title }}" class="w-full rounded-lg">
            </a>
        @endif
        @if(isset($beamslideUrl) && $beamslideUrl)
            <h5 class="mb-2">Beamslide preview</h5>
            <div class="rounded-lg overflow-hidden" style="aspect-ratio: 16/9;">
                <iframe src="{{ $beamslideUrl }}" class="w-full h-full border-0 rounded-lg" scrolling="no"></iframe>
            </div>
        @endif
    </div>
</div>
@endif

{{-- Work stages --}}
@if ($record->competition->competition_type->number_of_work_stages > 0)
<div class="rounded-lg bg-surface border border-border shadow-[0_4px_12px_rgba(0,0,0,0.4)] mb-4">
    <div class="px-5 py-3 bg-surface-raised rounded-t-lg text-heading font-semibold border-b border-border">
        <h4>Work stages</h4>
    </div>
    <div class="p-5">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @for ($i=1; $i<=$record->competition->competition_type->number_of_work_stages; $i++)
                @if($record->getFirstMedia('work_stage_'.$i))
                    <a data-caption="Work stage {{$i}}" data-fancybox="gallery"
                       href="{{$record->getFirstMedia('work_stage_'.$i)->getUrl('preview')}}" class="hover:opacity-90 transition-opacity block">
                        <img src="{{$record->getFirstMedia('work_stage_'.$i)->getUrl('preview')}}"
                             alt="Work stage {{$i}} for {{ $record->title }}" class="w-full rounded-lg">
                    </a>
                @endif
            @endfor
        </div>
    </div>
</div>
@endif

{{-- Files --}}
@if($record->getMedia('file')->count() > 0 || $record->getMedia('config_file')->count() > 0)
<div class="rounded-lg bg-surface border border-border shadow-[0_4px_12px_rgba(0,0,0,0.4)] mb-4">
    <div class="px-5 py-3 bg-surface-raised rounded-t-lg text-heading font-semibold border-b border-border">
        <h4>Files</h4>
    </div>
    <div class="p-5 space-y-2">
        @foreach($record->getMedia('file') as $file)
            <div class="flex justify-between items-center">
                <a href="{{$file->getUrl()}}" class="text-accent hover:text-accent-hover transition-colors">{{ $file->file_name }}</a>
                <span class="text-sm text-text-muted">{{ $file->created_at }}</span>
            </div>
        @endforeach
        @if($record->getMedia('config_file')->count() > 0)
            <div class="flex justify-between items-center">
                <a href="{{ $record->getFirstMedia('config_file')->getUrl() }}" class="text-accent hover:text-accent-hover transition-colors">{{ $record->getFirstMedia('config_file')->file_name }} (config)</a>
                <span class="text-sm text-text-muted">{{ $record->getFirstMedia('config_file')->created_at }}</span>
            </div>
        @endif
    </div>
</div>
@endif

{{-- Author & composer info --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div class="rounded-lg bg-surface border border-border shadow-[0_4px_12px_rgba(0,0,0,0.4)]">
        <div class="px-5 py-3 bg-surface-raised rounded-t-lg text-heading font-semibold border-b border-border">
            <h4>{{trans('partymeister-competitions::backend/entries.author_info')}}</h4>
        </div>
        <div class="p-5">
            <dl class="grid grid-cols-1 md:grid-cols-[1fr_2fr] gap-y-3 gap-x-4">
                <dt class="font-semibold">{{trans('partymeister-competitions::backend/entries.name')}}</dt>
                <dd>{{ $record->author_name }}</dd>
                <dt class="font-semibold">{{trans('partymeister-competitions::backend/entries.email')}}</dt>
                <dd>{{ $record->author_email }}</dd>
                <dt class="font-semibold">{{trans('partymeister-competitions::backend/entries.phone')}}</dt>
                <dd>{{ $record->author_phone }}</dd>
                <dt class="font-semibold">{{trans('partymeister-competitions::backend/entries.address')}}</dt>
                <dd>{{ $record->author_address }} {{ $record->author_zip }} {{ $record->author_city }} {{ $record->author_country }}</dd>
            </dl>
        </div>
    </div>

    @if ($record->competition->competition_type->has_composer)
        <div class="rounded-lg bg-surface border border-border shadow-[0_4px_12px_rgba(0,0,0,0.4)]">
            <div class="px-5 py-3 bg-surface-raised rounded-t-lg text-heading font-semibold border-b border-border">
                <h4>{{trans('partymeister-competitions::backend/entries.composer_info')}}</h4>
            </div>
            <div class="p-5">
                <dl class="grid grid-cols-1 md:grid-cols-[1fr_2fr] gap-y-3 gap-x-4">
                    <dt class="font-semibold">{{trans('partymeister-competitions::backend/entries.name')}}</dt>
                    <dd>{{ $record->composer_name }}</dd>
                    <dt class="font-semibold">{{trans('partymeister-competitions::backend/entries.email')}}</dt>
                    <dd>{{ $record->composer_email }}</dd>
                    <dt class="font-semibold">{{trans('partymeister-competitions::backend/entries.phone')}}</dt>
                    <dd>{{ $record->composer_phone }}</dd>
                    <dt class="font-semibold">{{trans('partymeister-competitions::backend/entries.address')}}</dt>
                    <dd>{{ $record->composer_address }} {{ $record->composer_zip }} {{ $record->composer_city }} {{ $record->composer_country }}</dd>
                </dl>
            </div>
        </div>
    @endif
</div>
