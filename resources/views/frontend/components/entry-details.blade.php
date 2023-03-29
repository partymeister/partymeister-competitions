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
<h4>
    @if ($record->is_remote)
        <span class="badge badge-danger">REMOTE</span>
    @endif
    Entry detail for: {{$record->title}} by {{$record->author}}
</h4>

<div class="grid-x">
    <div class="medium-6">
        <dl class="row">
            <dt class="small-4">
                Identifier
            </dt>
            <dd class="small-8">
                {{ $record->identifier }}
            </dd>

            <dt class="small-4">
                {{trans('partymeister-competitions::backend/competitions.competition')}}
            </dt>
            <dd class="small-8">
                {{ $record->competition->name }}
            </dd>

            @if($record->competition->competition_type->has_running_time)
                <dt class="small-4">
                    {{trans('partymeister-competitions::backend/entries.running_time')}}
                </dt>
                <dd class="small-8">
                    {{ $record->running_time }}
                </dd>
            @endif

            <dt class="small-4">
                {{trans('partymeister-competitions::backend/entries.description')}}
            </dt>
            <dd class="small-8">
                <p>{{nl2br($record->description)}}</p>
            </dd>

            <dt class="small-4">
                {{trans('partymeister-competitions::backend/entries.organizer_description')}}
            </dt>
            <dd class="small-8">
                <p>{{nl2br($record->organizer_description)}}</p>
            </dd>
            <dt class="small-4">
                {{trans('partymeister-competitions::backend/entries.notify_about_status')}}
            </dt>
            <dd class="small-8">
                <p>{{$record->notify_about_status ? 'Yes' : 'No'}}</p>
            </dd>

            @if ($record->discord_name !== '')
            <dt class="small-4">
                {{trans('partymeister-competitions::backend/entries.discord_name_short')}}
            </dt>
            <dd class="small-8">
                <p>{{nl2br($record->discord_name)}}</p>
            </dd>
            @endif
            @if ($record->representative !== '')
                <dt class="small-4">
                    {{trans('partymeister-competitions::backend/entries.representative')}}
                </dt>
                <dd class="small-8">
                    <p>{{nl2br($record->representative)}}</p>
                </dd>
            @endif
        </dl>
    </div>
    <div class="medium-6">
        <dl class="row">
            @if ($record->options->count() > 0)
                <dt class="small-4">
                    {{trans('partymeister-competitions::backend/entries.option_info')}}
                </dt>
                <dd class="small-8">
                    <ul class="list-unstyled">
                        @foreach ($record->options as $option)
                            <li>{{$option->name}}</li>
                        @endforeach
                    </ul>
                </dd>
            @endif
            <dt class="small-4">
                {{trans('partymeister-competitions::backend/entries.custom_option_short')}}
            </dt>
            <dd class="small-8">
                {{ $record->custom_option }}
            </dd>
        </dl>
    </div>
</div>

<div class="row clearfix">
    <div class="medium-12">
        @if($record->getFirstMedia('screenshot'))
            <h3>Screenshot</h3>
            <div class="card">
                <a data-caption="{{$record->title}} by {{$record->author}}" data-fancybox="gallery"
                   href="{{$record->getFirstMedia('screenshot')->getUrl('preview')}}">
                    <img src="{{$record->getFirstMedia('screenshot')->getUrl('preview')}}" class="img-fluid">
                </a>
            </div>
        @endif
        <h3>Beamslide preview</h3>
        <div id="app">
            <partymeister-slides-elements class="slidemeister-template"
                                          :readonly="true" :id="'template-preview'"
                                          :name="'template-preview'">

            </partymeister-slides-elements>
        </div>
    </div>
    @if ($record->competition->competition_type->number_of_work_stages > 0)
        <div class="medium-12">
            <h3>Work stages</h3>
            <div class="row">
                @for ($i=1; $i<=$record->competition->competition_type->number_of_work_stages; $i++)
                    <div class="col-md-6">
                        <div class="card">
                            @if($record->getFirstMedia('work_stage_'.$i))
                                <a data-caption="Work stage {{$i}}" data-fancybox="gallery"
                                   href="{{$record->getFirstMedia('work_stage_'.$i)->getUrl('preview')}}">
                                    <img src="{{$record->getFirstMedia('work_stage_'.$i)->getUrl('preview')}}"
                                         class="img-fluid">
                                </a>
                            @endif
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    @endif

    @if($record->getMedia('file')->count() > 0)
        <div class="medium-12">
            <h3>Files</h3>
            @foreach($record->getMedia('file') as $file)
                <div class="float-left">
                    <a href="{{$file->getUrl()}}">{{ $file->file_name }}</a>
                </div>
                <div class="float-right">
                    {{trans('motor-backend::backend/global.uploaded')}} {{ $file->created_at }}<br>
                </div>
                <div class="clearfix"></div>
            @endforeach
        </div>
    @endif

    @if($record->getMedia('config_file')->count() > 0)
        <div class="medium-12">
            <h3>Config file</h3>
            <div class="float-left">
                <a href="{{ $record->getFirstMedia('config_file')->getUrl() }}">{{ $record->getFirstMedia('config_file')->file_name }}</a>
            </div>
            <div class="float-right">
                {{trans('motor-backend::backend/global.uploaded')}} {{ $record->getFirstMedia('config_file')->created_at }}
                <br>
            </div>
            <div class="clearfix"></div>
        </div>
        <p></p>
    @endif

    <div class="medium-6">
        <h3>{{trans('partymeister-competitions::backend/entries.author_info')}}</h3>
        <dl class="row">
            <dt class="small-4">
                {{trans('partymeister-competitions::backend/entries.name')}}
            </dt>
            <dd class="small-8">
                {{ $record->author_name }}
            </dd>

            <dt class="small-4">
                {{trans('partymeister-competitions::backend/entries.email')}}
            </dt>
            <dd class="small-8">
                {{ $record->author_email }}
            </dd>

            <dt class="small-4">
                {{trans('partymeister-competitions::backend/entries.phone')}}
            </dt>
            <dd class="small-8">
                {{ $record->author_phone }}
            </dd>

            <dt class="small-4">
                {{trans('partymeister-competitions::backend/entries.address')}}
            </dt>
            <dd class="small-8">
                {{ $record->author_address }} {{ $record->author_zip }} {{ $record->author_city }} {{
                    $record->author_country }}
            </dd>
        </dl>
    </div>

    @if ($record->competition->competition_type->has_composer)
        <div class="medium-6">
            <h3>{{trans('partymeister-competitions::backend/entries.composer_info')}}</h3>
            <dl class="row">
                <dt class="small-4">
                    {{trans('partymeister-competitions::backend/entries.name')}}
                </dt>
                <dd class="small-8">
                    {{ $record->composer_name }}
                </dd>

                <dt class="small-4">
                    {{trans('partymeister-competitions::backend/entries.email')}}
                </dt>
                <dd class="small-8">
                    {{ $record->composer_email }}
                </dd>

                <dt class="small-4">
                    {{trans('partymeister-competitions::backend/entries.phone')}}
                </dt>
                <dd class="small-8">
                    {{ $record->composer_phone }}
                </dd>

                <dt class="small-4">
                    {{trans('partymeister-competitions::backend/entries.address')}}
                </dt>
                <dd class="small-8">
                    {{ $record->composer_address }} {{ $record->composer_zip }} {{ $record->composer_city }}
                    {{ $record->composer_country }}
                </dd>
            </dl>
        </div>
    @endif
</div>


@section('view-scripts')
    <script src="{{mix('js/slidemeister.js')}}"></script>
    <script>
        $(document).ready(function () {

            Vue.prototype.$eventHub.$emit('partymeister-slides:load-definitions', {
                name: 'template-preview',
                elements: JSON.parse('{!! addslashes($competitionTemplate->definitions) !!}'),
                type: 'competition-entry',
                replacements: {!! json_encode($entry) !!},
            });

            function resize() {
                let width = $('#app').parent().width();
                let zoom = width / 960;
                $('#app').css('zoom', zoom);
                $('#app').css('height', 560);
            }

            resize();

            {{--let sm = $('#slidemeister-competition-preview').slidemeister('#slidemeister-properties', slidemeisterProperties);--}}
            {{--sm.data.load({!! $competitionTemplate->definitions !!}, {!! json_encode($entry) !!}, false, true);--}}
            {{--resize();--}}

            $(window).resize(function () {
                resize();
            });
        });
    </script>
@append
