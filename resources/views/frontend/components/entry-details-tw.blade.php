@section('view-styles')
    @include('partymeister-slides::layouts.partials.slide_fonts')
    <style type="text/css">
        .slidemeister-instance {
            zoom: 0.75;
            float: left;
            margin-right: 15px;
            margin-bottom: 15px;

            clip-path: inset(0);
            background-color: #fff;
            border: 1px solid black;
            position: relative;
            width: 960px;
            height: 540px;
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center center;
        }

        .slidemeister-element {
            position: absolute;
            display: flex;
            width: 200px;
            height: 100px;
            left: 50px;
            top: 50px;
            background-color: transparent;
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center center;
            border: 2px solid transparent;
            padding: 0;
            margin: 0;
        }
    </style>
@append
@include('motor-backend::errors.list')
<h4 class="text-lg font-bold mb-4">
    @if ($record->is_remote)
        <span class="badge badge-error">REMOTE</span>
    @endif
    Entry detail for: {{$record->title}} by {{$record->author}}
</h4>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6" x-data="entryDetails">
    <div>
        <dl class="grid grid-cols-[1fr_2fr] gap-y-2 gap-x-4">
            <dt class="font-semibold">
                Identifier
            </dt>
            <dd>
                {{ $record->identifier }}
            </dd>

            <dt class="font-semibold">
                {{trans('partymeister-competitions::backend/competitions.competition')}}
            </dt>
            <dd>
                {{ $record->competition->name }}
            </dd>

            @if($record->competition->competition_type->has_running_time)
                <dt class="font-semibold">
                    {{trans('partymeister-competitions::backend/entries.running_time')}}
                </dt>
                <dd>
                    {{ $record->running_time }}
                </dd>
            @endif

            <dt class="font-semibold">
                {{trans('partymeister-competitions::backend/entries.description')}}
            </dt>
            <dd>
                <p>{{nl2br($record->description)}}</p>
            </dd>

            <dt class="font-semibold">
                {{trans('partymeister-competitions::backend/entries.organizer_description')}}
            </dt>
            <dd>
                <p>{{nl2br($record->organizer_description)}}</p>
            </dd>
            <dt class="font-semibold">
                {{trans('partymeister-competitions::backend/entries.notify_about_status')}}
            </dt>
            <dd>
                <p>{{$record->notify_about_status ? 'Yes' : 'No'}}</p>
            </dd>

            @if ($record->discord_name !== '')
            <dt class="font-semibold">
                {{trans('partymeister-competitions::backend/entries.discord_name_short')}}
            </dt>
            <dd>
                <p>{{nl2br($record->discord_name)}}</p>
            </dd>
            @endif
            @if ($record->representative !== '')
                <dt class="font-semibold">
                    {{trans('partymeister-competitions::backend/entries.representative')}}
                </dt>
                <dd>
                    <p>{{nl2br($record->representative)}}</p>
                </dd>
            @endif
        </dl>
    </div>
    <div>
        <dl class="grid grid-cols-[1fr_2fr] gap-y-2 gap-x-4">
            @if ($record->options->count() > 0)
                <dt class="font-semibold">
                    {{trans('partymeister-competitions::backend/entries.option_info')}}
                </dt>
                <dd>
                    <ul class="list-disc list-inside">
                        @foreach ($record->options as $option)
                            <li>{{$option->name}}</li>
                        @endforeach
                    </ul>
                </dd>
            @endif
            @if ($record->custom_option != '')
            <dt class="font-semibold">
                {{trans('partymeister-competitions::backend/entries.custom_option_short')}}
            </dt>
            <dd>
                {{ $record->custom_option }}
            </dd>
            @endif

            @if ($record->ai_data && $record->ai_usage != '')
            <dt class="font-semibold">
                {{trans('partymeister-competitions::backend/entries.ai_information')}}
            </dt>
            <dd>
                <b>{{ trans('partymeister-competitions::backend/entries.ai_usage') }}: {{ trans('partymeister-competitions::backend/entries.ai_usage_options.' . $record->ai_usage) }}</b>
                <p>
                    {{ $record->ai_usage_description }}
                </p>
            </dd>
            @endif

            @if ($record->engine_data && $record->engine_option != '')
            <dt class="font-semibold">
                {{trans('partymeister-competitions::backend/entries.engine_information')}}
            </dt>
            <dd>
                <b>{{ trans('partymeister-competitions::backend/entries.engine_option') }}: {{ trans('partymeister-competitions::backend/entries.engine_options.' . $record->engine_option) }}</b>
                <p>
                    <b>Engine:</b> {{ $record->engine_option_description }}
                </p>
                <p>
                    <b>Creator involvement:</b> {{ trans('partymeister-competitions::backend/entries.engine_creator_involvement_options.' . $record->engine_creator_involvement) }}
                </p>
            </dd>
            @endif

        </dl>
    </div>
</div>

<div class="mt-6">
    <div>
        @if($record->getFirstMedia('screenshot'))
            <h3 class="text-xl font-bold mb-2">Screenshot</h3>
            <div class="card bg-base-200 shadow-md mb-4">
                <figure>
                    <a data-caption="{{$record->title}} by {{$record->author}}" data-fancybox="gallery"
                       href="{{$record->getFirstMedia('screenshot')->getUrl('preview')}}">
                        <img src="{{$record->getFirstMedia('screenshot')->getUrl('preview')}}" class="w-full">
                    </a>
                </figure>
            </div>
        @endif
        <h3 class="text-xl font-bold mb-2">Beamslide preview</h3>
        <div id="app" x-ref="appContainer">
            <partymeister-slides-elements class="slidemeister-template"
                                          :readonly="true" :id="'template-preview'"
                                          :name="'template-preview'">

            </partymeister-slides-elements>
        </div>
    </div>
    @if ($record->competition->competition_type->number_of_work_stages > 0)
        <div class="mt-6">
            <h3 class="text-xl font-bold mb-2">Work stages</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @for ($i=1; $i<=$record->competition->competition_type->number_of_work_stages; $i++)
                    <div>
                        <div class="card bg-base-200 shadow-md">
                            @if($record->getFirstMedia('work_stage_'.$i))
                                <figure>
                                    <a data-caption="Work stage {{$i}}" data-fancybox="gallery"
                                       href="{{$record->getFirstMedia('work_stage_'.$i)->getUrl('preview')}}">
                                        <img src="{{$record->getFirstMedia('work_stage_'.$i)->getUrl('preview')}}"
                                             class="w-full">
                                    </a>
                                </figure>
                            @endif
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    @endif

    @if($record->getMedia('file')->count() > 0)
        <div class="mt-6">
            <h3 class="text-xl font-bold mb-2">Files</h3>
            @foreach($record->getMedia('file') as $file)
                <div class="flex justify-between items-center py-1">
                    <a href="{{$file->getUrl()}}" class="link link-primary">{{ $file->file_name }}</a>
                    <span class="text-sm opacity-70">{{trans('motor-backend::backend/global.uploaded')}} {{ $file->created_at }}</span>
                </div>
            @endforeach
        </div>
    @endif

    @if($record->getMedia('config_file')->count() > 0)
        <div class="mt-6">
            <h3 class="text-xl font-bold mb-2">Config file</h3>
            <div class="flex justify-between items-center py-1">
                <a href="{{ $record->getFirstMedia('config_file')->getUrl() }}" class="link link-primary">{{ $record->getFirstMedia('config_file')->file_name }}</a>
                <span class="text-sm opacity-70">{{trans('motor-backend::backend/global.uploaded')}} {{ $record->getFirstMedia('config_file')->created_at }}</span>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
        <div>
            <h3 class="text-xl font-bold mb-2">{{trans('partymeister-competitions::backend/entries.author_info')}}</h3>
            <dl class="grid grid-cols-[1fr_2fr] gap-y-2 gap-x-4">
                <dt class="font-semibold">
                    {{trans('partymeister-competitions::backend/entries.name')}}
                </dt>
                <dd>
                    {{ $record->author_name }}
                </dd>

                <dt class="font-semibold">
                    {{trans('partymeister-competitions::backend/entries.email')}}
                </dt>
                <dd>
                    {{ $record->author_email }}
                </dd>

                <dt class="font-semibold">
                    {{trans('partymeister-competitions::backend/entries.phone')}}
                </dt>
                <dd>
                    {{ $record->author_phone }}
                </dd>

                <dt class="font-semibold">
                    {{trans('partymeister-competitions::backend/entries.address')}}
                </dt>
                <dd>
                    {{ $record->author_address }} {{ $record->author_zip }} {{ $record->author_city }} {{
                        $record->author_country }}
                </dd>
            </dl>
        </div>

        @if ($record->competition->competition_type->has_composer)
            <div>
                <h3 class="text-xl font-bold mb-2">{{trans('partymeister-competitions::backend/entries.composer_info')}}</h3>
                <dl class="grid grid-cols-[1fr_2fr] gap-y-2 gap-x-4">
                    <dt class="font-semibold">
                        {{trans('partymeister-competitions::backend/entries.name')}}
                    </dt>
                    <dd>
                        {{ $record->composer_name }}
                    </dd>

                    <dt class="font-semibold">
                        {{trans('partymeister-competitions::backend/entries.email')}}
                    </dt>
                    <dd>
                        {{ $record->composer_email }}
                    </dd>

                    <dt class="font-semibold">
                        {{trans('partymeister-competitions::backend/entries.phone')}}
                    </dt>
                    <dd>
                        {{ $record->composer_phone }}
                    </dd>

                    <dt class="font-semibold">
                        {{trans('partymeister-competitions::backend/entries.address')}}
                    </dt>
                    <dd>
                        {{ $record->composer_address }} {{ $record->composer_zip }} {{ $record->composer_city }}
                        {{ $record->composer_country }}
                    </dd>
                </dl>
            </div>
        @endif
    </div>
</div>


@section('view-scripts')
    <script type="module">
        document.addEventListener('DOMContentLoaded', function () {

            window.eventBus.emit('partymeister-slides:load-definitions', {
                name: 'template-preview',
                elements: JSON.parse('{!! addslashes($competitionTemplate->definitions) !!}'),
                type: 'competition-entry',
                replacements: {!! json_encode($entry) !!},
            });

            function resize() {
                let appEl = document.getElementById('app');
                let width = appEl.parentElement.offsetWidth;
                let zoom = width / 960;
                appEl.style.zoom = zoom;
                appEl.style.height = '560px';
            }

            resize();

            window.addEventListener('resize', function () {
                resize();
            });
        });
    </script>
@append
