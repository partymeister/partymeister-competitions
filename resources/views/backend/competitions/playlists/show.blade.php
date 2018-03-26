@extends('motor-backend::layouts.backend')

@section('view_styles')
    @include('partymeister-slides::layouts.partials.slide_fonts')
    <style type="text/css">
        .slidemeister-instance {
            zoom: 0.75;
            float: left;
            margin-right: 15px;
            margin-bottom: 15px;
        }
    </style>
@append
@section('htmlheader_title')
    {{ trans('motor-backend::backend/global.home') }}
@endsection

@section('contentheader_title')
    {{ trans('partymeister-competitions::backend/competitions.playlist_preview') }}
    <button class="btn btn-sm btn-success float-right competition-playlist-save">{{trans('partymeister-competitions::backend/competitions.save_playlist')}}</button>
    {!! link_to_route('backend.competitions.index', trans('motor-backend::backend/global.back'), [], ['class' => 'float-right btn btn-sm btn-danger']) !!}
@endsection

@section('main-content')
    <form id="competition-playlist-save"
          action="{{route('backend.competitions.playlist.store', ['competition' => $competition->id])}}" method="POST">
        @csrf
        <div class="@boxWrapper box-primary" style="margin-bottom: 0;">
            <div class="@boxBody">
                <div id="slidemeister-competition-comingup" class="slidemeister-instance"></div>
                <input type="hidden" name="slide[comingup]">
                <input type="hidden" name="type[comingup]" value="comingup">
                <input type="hidden" name="name[comingup]" value="Coming up">
                @foreach ($videos as $index => $video)
                    <div class="slidemeister-instance">
                        <img src="{{$video['data']['preview']}}" style="width: 100%;">
                        <input type="hidden" name="slide[video_{{$index+1}}]"
                               value="{{ json_encode($video, JSON_UNESCAPED_SLASHES) }}">
                        <input type="hidden" name="type[video_{{$index+1}}]" value="video_{{$index+1}}">
                        <input type="hidden" name="name[video_{{$index+1}}]" value="Video {{$index+1}}">
                    </div>
                @endforeach
                <div id="slidemeister-competition-now" class="slidemeister-instance"></div>
                <input type="hidden" name="slide[now]">
                <input type="hidden" name="type[now]" value="now">
                <input type="hidden" name="name[now]" value="Now">
                @foreach($entries as $index => $entry)
                    <div id="slidemeister-entry-{{$entry['id']}}" class="slidemeister-instance"></div>
                    <input type="hidden" name="slide[entry_{{$entry['id']}}]">
                    <input type="hidden" name="type[entry_{{$entry['id']}}]" value="entry">
                    <input type="hidden" name="name[entry_{{$entry['id']}}]" value="Entry #{{$index+1}}">
                    <input type="hidden" name="id[entry_{{$entry['id']}}]" value="{{$entry['id']}}">
                @endforeach
                @if (count($participants) > 0)
                    <div id="slidemeister-competition-participants" class="slidemeister-instance"></div>
                    <input type="hidden" name="slide[participants]">
                    <input type="hidden" name="type[participants]" value="participants">
                    <input type="hidden" name="name[participants]" value="Participants">
                @endif
                <div id="slidemeister-competition-end" class="slidemeister-instance"></div>
                <input type="hidden" name="slide[end]">
                <input type="hidden" name="type[end]" value="end">
                <input type="hidden" name="name[end]" value="End">
            </div>
        </div>
    </form>
@endsection

@section('view_scripts')
    @include('partymeister-slides::layouts.partials.slide_scripts')
    <script>
        $(document).ready(function () {

            var sm = [];
            sm['comingup'] = $('#slidemeister-competition-comingup').slidemeister('#slidemeister-properties', slidemeisterProperties);
            sm['comingup'].data.load({!! $comingupTemplate->definitions !!}, {
                'competition': '{{strtoupper($competition->name)}}',
                'headline': 'COMING UP'
            }, false, true);
            sm['now'] = $('#slidemeister-competition-now').slidemeister('#slidemeister-properties', slidemeisterProperties);
            sm['now'].data.load({!! $comingupTemplate->definitions !!}, {
                'competition': '{{strtoupper($competition->name)}}',
                'headline': 'NOW'
            }, false, true);
            @foreach($entries as $entry)
                sm['entry_{{$entry['id']}}'] = $('#slidemeister-entry-{{$entry['id']}}').slidemeister('#slidemeister-properties', slidemeisterProperties);
            sm['entry_{{$entry['id']}}'].data.load({!! $entryTemplate->definitions !!}, {!! json_encode($entry) !!}, false, true);
            @endforeach
            @if (count($participants) > 0)
                sm['participants'] = $('#slidemeister-competition-participants').slidemeister('#slidemeister-properties', slidemeisterProperties);
                sm['participants'].data.load({!! $participantsTemplate->definitions !!}, {
                    'competition': 'PARTICIPANTS',
                    'headline': '{{strtoupper($competition->name)}}',
                    'body': '{{implode(', ', $participants)}}'
                }, false, true);

            @endif
                sm['end'] = $('#slidemeister-competition-end').slidemeister('#slidemeister-properties', slidemeisterProperties);
            sm['end'].data.load({!! $endTemplate->definitions !!}, {
                'competition': '{{strtoupper($competition->name)}}',
                'headline': 'END'
            }, false, true);

            $('.competition-playlist-save').on('click', function (e) {

                Object.keys(sm).forEach(function (key) {
                    $('input[name="slide[' + key + ']"]').val(JSON.stringify(sm[key].data.save(true)));
                    $('form#competition-playlist-save').submit();
                });
            });
        });
    </script>
@append