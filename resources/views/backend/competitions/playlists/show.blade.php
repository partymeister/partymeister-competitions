@extends('motor-backend::layouts.backend')
@section('view_styles')
    @include('partymeister-slides::layouts.partials.slide_fonts')
    <style type="text/css">
        .slidemeister-instance {
            zoom: 1;
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
    <button class="btn btn-sm btn-success float-right competition-playlist-save"
            disabled>{{trans('partymeister-competitions::backend/competitions.save_playlist')}}</button>
    {!! link_to_route('backend.competitions.index', trans('motor-backend::backend/global.back'), [], ['class' => 'float-right btn btn-sm btn-danger']) !!}
@endsection

@section('main-content')
    @if (isset($message))
        <div class="alert alert-warning">
            {{$message}}
        </div>
    @else
        <form id="competition-playlist-save"
              action="{{route('backend.competitions.playlist.store', ['competition' => $competition->id])}}"
              method="POST">
            @csrf
            <div class="@boxWrapper box-primary" style="margin-bottom: 0;">
                <div class="@boxBody">
                    <partymeister-slides-elements :readonly="true" :name="'slidemeister-competition-comingup'"
                                                  id="slidemeister-competition-comingup"
                                                  class="slidemeister-instance slide"></partymeister-slides-elements>
                    <input type="hidden" name="slide[comingup]">
                    <input type="hidden" name="name[comingup]"
                           value="Coming up">
                    <input type="hidden" name="cached_html_preview[comingup]">
                    <input type="hidden" name="cached_html_final[comingup]">
                    <input type="hidden" name="type[comingup]" value="comingup">

                    @foreach ($videos as $index => $video)
                        <div class="slidemeister-instance video">
                            <img src="{{$video['data']['preview']}}" style="width: 100%;">
                            <input type="hidden" name="slide[video_{{$index+1}}]"
                                   value="{{ json_encode($video, JSON_UNESCAPED_SLASHES) }}">
                            <input type="hidden" name="type[video_{{$index+1}}]" value="video_{{$index+1}}">
                            <input type="hidden" name="name[video_{{$index+1}}]" value="Video {{$index+1}}">
                        </div>
                    @endforeach

                    <partymeister-slides-elements :readonly="true" :name="'slidemeister-competition-now'"
                                                  id="slidemeister-competition-now"
                                                  class="slidemeister-instance slide"></partymeister-slides-elements>
                    <input type="hidden" name="slide[now]">
                    <input type="hidden" name="name[now]"
                           value="Now">
                    <input type="hidden" name="cached_html_preview[now]">
                    <input type="hidden" name="cached_html_final[now]">
                    <input type="hidden" name="type[now]" value="now">

                    @foreach($entries as $index => $entry)
                        <partymeister-slides-elements :readonly="true"
                                                      :name="'slidemeister-competition-entry-{{$entry['id']}}'"
                                                      id="slidemeister-competition-entry-{{$entry['id']}}"
                                                      class="slidemeister-instance slide"></partymeister-slides-elements>
                        <input type="hidden" name="slide[entry_{{$entry['id']}}]">
                        <input type="hidden" name="name[entry_{{$entry['id']}}]"
                               value="Now">
                        <input type="hidden" name="cached_html_preview[entry_{{$entry['id']}}]">
                        <input type="hidden" name="cached_html_final[entry_{{$entry['id']}}]">
                        <input type="hidden" name="name[entry_{{$entry['id']}}]" value="Now">
                        <input type="hidden" name="type[entry_{{$entry['id']}}]" value="entry">
                        <input type="hidden" name="name[entry_{{$entry['id']}}]" value="Entry #{{$index+1}}">
                        <input type="hidden" name="id[entry_{{$entry['id']}}]" value="{{$entry['id']}}">
                    @endforeach
                    @if (count($participants) > 0)
                        <partymeister-slides-elements :readonly="true" :name="'slidemeister-competition-participants'"
                                                      id="slidemeister-competition-participants"
                                                      class="slidemeister-instance slide"></partymeister-slides-elements>
                        <input type="hidden" name="slide[participants]">
                        <input type="hidden" name="name[participants]"
                               value="Participants">
                        <input type="hidden" name="cached_html_preview[participants]">
                        <input type="hidden" name="cached_html_final[participants]">
                        <input type="hidden" name="type[participants]" value="participants">
                    @endif

                    <partymeister-slides-elements :readonly="true" :name="'slidemeister-competition-end'"
                                                  id="slidemeister-competition-end"
                                                  class="slidemeister-instance slide"></partymeister-slides-elements>
                    <input type="hidden" name="slide[end]">
                    <input type="hidden" name="name[end]"
                           value="End">
                    <input type="hidden" name="cached_html_preview[end]">
                    <input type="hidden" name="cached_html_final[end]">
                    <input type="hidden" name="type[end]" value="end">
                </div>
            </div>
        </form>
    @endif
    <div class="loader loader-default"
         data-text="&hearts; {{ trans('partymeister-slides::backend/slides.generating') }} &hearts;"></div>
@endsection

@if (!isset($message))
    @section('view_scripts')
        <script>
            $(document).ready(function () {
                Vue.prototype.$eventHub.$emit('partymeister-slides:load-definitions', {
                    name: 'slidemeister-competition-comingup',
                    elements: JSON.parse('{!! addslashes($comingupTemplate->definitions) !!}'),
                    type: 'competition-support',

                    replacements: {headline: 'Coming up', entry: {!! json_encode($entry) !!} },
                });

                Vue.prototype.$eventHub.$emit('partymeister-slides:load-definitions', {
                    name: 'slidemeister-competition-now',
                    elements: JSON.parse('{!! addslashes($nowTemplate->definitions) !!}'),
                    type: 'competition-support',
                    replacements: {headline: 'Now', entry: {!! json_encode($entry) !!} },
                });

                Vue.prototype.$eventHub.$emit('partymeister-slides:load-definitions', {
                    name: 'slidemeister-competition-end',
                    elements: JSON.parse('{!! addslashes($endTemplate->definitions) !!}'),
                    type: 'competition-support',
                    replacements: {headline: 'End', entry: {!! json_encode($entry) !!} },
                });

                @if (count($participants) > 0)
                Vue.prototype.$eventHub.$emit('partymeister-slides:load-definitions', {
                    name: 'slidemeister-competition-participants',
                    elements: JSON.parse('{!! addslashes($participantsTemplate->definitions) !!}'),
                    type: 'competition-participants',
                    replacements: '{{implode(', ', $participants)}}',
                });
                @endif

                @foreach($entries as $index => $entry)
                @if ($index === 0)
                Vue.prototype.$eventHub.$emit('partymeister-slides:load-definitions', {
                    name: 'slidemeister-competition-entry-{{$entry['id']}}',
                    elements: JSON.parse('{!! addslashes($firstEntryTemplate->definitions) !!}'),
                    type: 'competition-entry',
                    replacements: {!! json_encode($entry) !!},
                });
                @else
                Vue.prototype.$eventHub.$emit('partymeister-slides:load-definitions', {
                    name: 'slidemeister-competition-entry-{{$entry['id']}}',
                    elements: JSON.parse('{!! addslashes($entryTemplate->definitions) !!}'),
                    type: 'competition-entry',
                    replacements: {!! json_encode($entry) !!},
                });
                @endif
                @endforeach


                $('.competition-playlist-save').prop('disabled', false);

                $('.competition-playlist-save').on('click', function (e) {

                    $('.loader').addClass('is-active');

                    let saveCounter = 0;

                    Vue.prototype.$eventHub.$on('partymeister-slides:timetable-finished', () => {
                        if (saveCounter === $('.slidemeister-instance.slide').length) {
                            $('#competition-playlist-save').submit();
                        }
                    });

                    Vue.prototype.$eventHub.$on('partymeister-slides:receive-definitions', (data) => {
                        if (data.name === 'slidemeister-competition-comingup') {
                            $('input[name="slide[comingup]"]').val(data.definitions_as_form_data);
                            $('input[name="cached_html_preview[comingup]"]').val($('#slidemeister-competition-comingup').html());
                            $('input[name="cached_html_final[comingup]"]').val($('#slidemeister-competition-comingup').html());
                            saveCounter++;
                        }
                        if (data.name === 'slidemeister-competition-now') {
                            $('input[name="slide[now]"]').val(data.definitions_as_form_data);
                            $('input[name="cached_html_preview[now]"]').val($('#slidemeister-competition-now').html());
                            $('input[name="cached_html_final[now]"]').val($('#slidemeister-competition-now').html());
                            saveCounter++;
                        }
                        if (data.name === 'slidemeister-competition-end') {
                            $('input[name="slide[end]"]').val(data.definitions_as_form_data);
                            $('input[name="cached_html_preview[end]"]').val($('#slidemeister-competition-end').html());
                            $('input[name="cached_html_final[end]"]').val($('#slidemeister-competition-end').html());
                            saveCounter++;
                        }
                        if (data.name === 'slidemeister-competition-participants') {
                            $('input[name="slide[participants]"]').val(data.definitions_as_form_data);
                            $('input[name="cached_html_preview[participants]"]').val($('#slidemeister-competition-participants').html());
                            $('input[name="cached_html_final[participants]"]').val($('#slidemeister-competition-participants').html());
                            saveCounter++;
                        }
                        @foreach($entries as $index => $entry)
                        if (data.name === 'slidemeister-competition-entry-{{$entry['id']}}') {
                            $('input[name="slide[entry_{{$entry['id']}}]"]').val(data.definitions_as_form_data);
                            $('input[name="cached_html_preview[entry_{{$entry['id']}}]"]').val($('#slidemeister-competition-entry-{{$entry['id']}}').html());
                            $('input[name="cached_html_final[entry_{{$entry['id']}}]"]').val($('#slidemeister-competition-entry-{{$entry['id']}}').html());
                            saveCounter++;
                        }
                        @endforeach

                        Vue.prototype.$eventHub.$emit('partymeister-slides:timetable-finished');
                    });

                    Vue.prototype.$eventHub.$emit('partymeister-slides:request-definitions', 'slidemeister-competition-comingup');
                    Vue.prototype.$eventHub.$emit('partymeister-slides:request-definitions', 'slidemeister-competition-now');
                    Vue.prototype.$eventHub.$emit('partymeister-slides:request-definitions', 'slidemeister-competition-end');
                    @if (count($participants) > 0)
                    Vue.prototype.$eventHub.$emit('partymeister-slides:request-definitions', 'slidemeister-competition-participants');
                    @endif
                    @foreach($entries as $index => $entry)
                    Vue.prototype.$eventHub.$emit('partymeister-slides:request-definitions', 'slidemeister-competition-entry-{{$entry['id']}}');
                    @endforeach
                });
            });
        </script>
    @append
@endif
