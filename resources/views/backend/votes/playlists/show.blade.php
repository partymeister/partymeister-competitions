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
    <button class="btn btn-sm btn-success float-right prizegiving-playlist-save"
            disabled>{{trans('partymeister-competitions::backend/competitions.save_playlist')}}</button>
    {!! link_to_route('backend.competitions.index', trans('motor-backend::backend/global.back'), [], ['class' => 'float-right btn btn-sm btn-danger']) !!}
@endsection

@section('main-content')
    @if (isset($message))
        <div class="alert alert-warning">
            {{$message}}
        </div>
    @else
        <form id="prizegiving-playlist-save"
              action="{{route('backend.votes.playlist.store')}}" method="POST">
            @csrf
            <div class="@boxWrapper box-primary" style="margin-bottom: 0;">
                <div class="@boxBody">

                    <partymeister-slides-elements :readonly="true" :name="'slidemeister-prizegiving-comingup'"
                                                  id="slidemeister-prizegiving-comingup"
                                                  class="slidemeister-instance"></partymeister-slides-elements>
                    <input type="hidden" name="slide[comingup]">
                    <input type="hidden" name="cached_html_preview[comingup]">
                    <input type="hidden" name="cached_html_final[comingup]">
                    <input type="hidden" name="type[comingup]" value="comingup">
                    <input type="hidden" name="name[comingup]" value="Prizegiving: Coming up">

                    <partymeister-slides-elements :readonly="true" :name="'slidemeister-prizegiving-now'"
                                                  id="slidemeister-prizegiving-now"
                                                  class="slidemeister-instance"></partymeister-slides-elements>

                    <input type="hidden" name="slide[now]">
                    <input type="hidden" name="cached_html_preview[now]">
                    <input type="hidden" name="cached_html_final[now]">
                    <input type="hidden" name="type[now]" value="now">
                    <input type="hidden" name="name[now]" value="Prizegiving: Now">

                    @foreach ($results as $key => $competition)
                        <partymeister-slides-elements :readonly="true" :name="'slidemeister-prizegiving-{{$key}}-now'"
                                                      id="slidemeister-prizegiving-{{$key}}-now"
                                                      class="slidemeister-instance"></partymeister-slides-elements>

                        <input type="hidden" name="slide[{{$key}}_now]">
                        <input type="hidden" name="cached_html_preview[{{$key}}_now]">
                        <input type="hidden" name="cached_html_final[{{$key}}_now]">
                        <input type="hidden" name="type[{{$key}}_now]" value="now">
                        <input type="hidden" name="name[{{$key}}_now]" value="Competition: {{$key}} Now">

                        @if ($competition['has_comment'] && $comments[$key] != '')
                            <partymeister-slides-elements :readonly="true" :name="'slidemeister-prizegiving-{{$key}}-comments'"
                                                          id="slidemeister-prizegiving-{{$key}}-comments"
                                                          class="slidemeister-instance"></partymeister-slides-elements>

                        <input type="hidden" name="slide[{{$key}}_comments]">
                        <input type="hidden" name="cached_html_preview[{{$key}}_comments]">
                        <input type="hidden" name="cached_html_final[{{$key}}_comments]">
                        <input type="hidden" name="type[{{$key}}_comments]" value="comments">
                        <input type="hidden" name="name[{{$key}}_comments]"
                               value="Competition: {{$key}} Comments">
                        @endif

                        <partymeister-slides-elements :readonly="true" :name="'slidemeister-prizegiving-{{$key}}-slide'"
                                                      id="slidemeister-prizegiving-{{$key}}-slide"
                                                      class="slidemeister-instance"></partymeister-slides-elements>

                        <input type="hidden" name="slide[{{$key}}_slide]">
                        <input type="hidden" name="cached_html_preview[{{$key}}_slide]">
                        <input type="hidden" name="cached_html_final[{{$key}}_slide]">
                        <input type="hidden" name="type[{{$key}}_slide]" value="siegmeister_bars">
                        <input type="hidden" name="name[{{$key}}_slide]" value="Competition: {{$key}} Bars">
                        <input type="hidden" name="meta[{{$key}}_slide]" value="Competition: {{$key}} Meta">

                        <partymeister-slides-elements :readonly="true" :name="'slidemeister-prizegiving-{{$key}}-winners'"
                                                      id="slidemeister-prizegiving-{{$key}}-winners"
                                                      class="slidemeister-instance"></partymeister-slides-elements>

                        <input type="hidden" name="slide[{{$key}}_winners]">
                        <input type="hidden" name="cached_html_preview[{{$key}}_winners]">
                        <input type="hidden" name="cached_html_final[{{$key}}_winners]">
                        <input type="hidden" name="type[{{$key}}_winners]" value="siegmeister_winners">
                        <input type="hidden" name="name[{{$key}}_winners]"
                               value="Competition: {{$key}} Winners">
                    @endforeach

                    @if (count($specialVotes) > 0)

                        <partymeister-slides-elements :readonly="true" :name="'slidemeister-prizegiving-special-now'"
                                                      id="slidemeister-prizegiving-special-now"
                                                      class="slidemeister-instance"></partymeister-slides-elements>

                    <input type="hidden" name="slide[special_now]">
                    <input type="hidden" name="cached_html_preview[special_now]">
                    <input type="hidden" name="cached_html_final[special_now]">
                    <input type="hidden" name="type[special_now]" value="now">
                    <input type="hidden" name="name[special_now]" value="Special: Now">

                        <partymeister-slides-elements :readonly="true" :name="'slidemeister-prizegiving-special-slide'"
                                                      id="slidemeister-prizegiving-special-slide"
                                                      class="slidemeister-instance"></partymeister-slides-elements>

                    <input type="hidden" name="slide[special_slide]">
                    <input type="hidden" name="cached_html_preview[special_slide]">
                    <input type="hidden" name="cached_html_final[special_slide]">
                    <input type="hidden" name="type[special_slide]" value="siegmeister_bars">
                    <input type="hidden" name="name[special_slide]" value="Special: Bars">
                    <input type="hidden" name="meta[special_slide]" value="Special: Meta">

                        <partymeister-slides-elements :readonly="true" :name="'slidemeister-prizegiving-special-winners'"
                                                      id="slidemeister-prizegiving-special-winners"
                                                      class="slidemeister-instance"></partymeister-slides-elements>

                    <input type="hidden" name="slide[special_winners]">
                    <input type="hidden" name="cached_html_preview[special_winners]">
                    <input type="hidden" name="cached_html_final[special_winners]">
                    <input type="hidden" name="type[special_winners]" value="siegmeister_winners">
                    <input type="hidden" name="name[special_winners]" value="Special: Winners">
                    @endif

                    <partymeister-slides-elements :readonly="true" :name="'slidemeister-prizegiving-end'"
                                                  id="slidemeister-prizegiving-end"
                                                  class="slidemeister-instance"></partymeister-slides-elements>

                    <input type="hidden" name="slide[end]">
                    <input type="hidden" name="cached_html_preview[end]">
                    <input type="hidden" name="cached_html_final[end]">
                    <input type="hidden" name="type[end]" value="end">
                    <input type="hidden" name="name[end]" value="Prizegiving: End">
                </div>
            </div>
        </form>
    @endif
    <div class="loader loader-default"
         data-text="&hearts; {{ trans('partymeister-slides::backend/slides.generating')}} &hearts;"></div>
@endsection

@if (!isset($message))
@section('view_scripts')
    <script>
        $(document).ready(function () {
            Vue.prototype.$eventHub.$emit('partymeister-slides:load-definitions', {
                name: 'slidemeister-prizegiving-comingup',
                elements: JSON.parse('{!! addslashes($comingupTemplate->definitions) !!}'),
                type: 'prizegiving-support',

                replacements: {headline: 'Coming up', body: 'Prizegiving' },
            });

            Vue.prototype.$eventHub.$emit('partymeister-slides:load-definitions', {
                name: 'slidemeister-prizegiving-now',
                elements: JSON.parse('{!! addslashes($nowTemplate->definitions) !!}'),
                type: 'prizegiving-support',
                replacements: {headline: 'Now', body: 'Prizegiving' },
            });

            Vue.prototype.$eventHub.$emit('partymeister-slides:load-definitions', {
                name: 'slidemeister-prizegiving-end',
                elements: JSON.parse('{!! addslashes($endTemplate->definitions) !!}'),
                type: 'prizegiving-support',
                replacements: {headline: 'End', body: 'Prizegiving' },
            });

            @foreach ($results as $key => $competition)
                Vue.prototype.$eventHub.$emit('partymeister-slides:load-definitions', {
                    name: 'slidemeister-prizegiving-{{$key}}-now',
                    elements: JSON.parse('{!! addslashes($comingupTemplate->definitions) !!}'),
                    type: 'prizegiving-support',
                    replacements: {headline: 'Now', body: '{{$competition['name']}}' },
                });
                @if ($competition['has_comment'] && $comments[$key] != '')
                    Vue.prototype.$eventHub.$emit('partymeister-slides:load-definitions', {
                        name: 'slidemeister-prizegiving-{{$key}}-comments',
                        elements: JSON.parse('{!! addslashes($commentsTemplate->definitions) !!}'),
                        type: 'prizegiving-support',
                        replacements: {headline: '{{$competition['name']}}', body: '{!! addslashes($comments[$key]) !!}' },
                    });
                @endif

                Vue.prototype.$eventHub.$emit('partymeister-slides:load-definitions', {
                    name: 'slidemeister-prizegiving-{{$key}}-slide',
                    elements: JSON.parse('{!! addslashes($prizegivingTemplate->definitions) !!}'),
                    type: 'prizegiving-slide',
                    replacements: {headline: '{{$competition["name"]}}', rows: {!! json_encode($competition['entries']) !!} },
                });

                Vue.prototype.$eventHub.$emit('partymeister-slides:load-definitions', {
                    name: 'slidemeister-prizegiving-{{$key}}-winners',
                    elements: JSON.parse('{!! addslashes($prizegivingTemplate->definitions) !!}'),
                    type: 'prizegiving-winners',
                    replacements: {headline: '{{$competition["name"]}}', rows: {!! json_encode($competition['entries']) !!} },
                });
            @endforeach
            @if (count($specialVotes) > 0)
            Vue.prototype.$eventHub.$emit('partymeister-slides:load-definitions', {
                name: 'slidemeister-prizegiving-special-now',
                elements: JSON.parse('{!! addslashes($comingupTemplate->definitions) !!}'),
                type: 'prizegiving-support',
                replacements: {headline: 'Now', body: 'Crowd favourite' },
            });

            Vue.prototype.$eventHub.$emit('partymeister-slides:load-definitions', {
                name: 'slidemeister-prizegiving-special-slide',
                elements: JSON.parse('{!! addslashes($prizegivingTemplate->definitions) !!}'),
                type: 'prizegiving-slide',
                replacements: {headline: 'Crowd favourite', rows: {!! json_encode($specialVotes) !!} },
            });

            Vue.prototype.$eventHub.$emit('partymeister-slides:load-definitions', {
                name: 'slidemeister-prizegiving-special-winners',
                elements: JSON.parse('{!! addslashes($prizegivingTemplate->definitions) !!}'),
                type: 'prizegiving-winners',
                replacements: {headline: 'Crowd favourite', rows: {!! json_encode($specialVotes) !!} },
            });
            @endif

            $('.prizegiving-playlist-save').prop('disabled', false);

            $('.prizegiving-playlist-save').on('click', function (e) {

                $('.loader').addClass('is-active');

                let saveCounter = 0;

                Vue.prototype.$eventHub.$on('partymeister-slides:timetable-finished', () => {
                    if (saveCounter === $('.slidemeister-instance').length) {
                        $('#prizegiving-playlist-save').submit();
                    }
                });

                Vue.prototype.$eventHub.$on('partymeister-slides:receive-definitions', (data) => {
                    if (data.name === 'slidemeister-prizegiving-comingup') {
                        $('input[name="slide[comingup]"]').val(data.definitions_as_form_data);
                        $('input[name="cached_html_preview[comingup]"]').val($('#slidemeister-prizegiving-comingup').html());
                        $('input[name="cached_html_final[comingup]"]').val($('#slidemeister-prizegiving-comingup').html());
                        saveCounter++;
                    }
                    if (data.name === 'slidemeister-prizegiving-now') {
                        $('input[name="slide[now]"]').val(data.definitions_as_form_data);
                        $('input[name="cached_html_preview[now]"]').val($('#slidemeister-prizegiving-now').html());
                        $('input[name="cached_html_final[now]"]').val($('#slidemeister-prizegiving-now').html());
                        saveCounter++;
                    }
                    if (data.name === 'slidemeister-prizegiving-end') {
                        $('input[name="slide[end]"]').val(data.definitions_as_form_data);
                        $('input[name="cached_html_preview[end]"]').val($('#slidemeister-prizegiving-end').html());
                        $('input[name="cached_html_final[end]"]').val($('#slidemeister-prizegiving-end').html());
                        saveCounter++;
                    }
                    @foreach ($results as $key => $competition)
                    @if ($competition['has_comment'])
                    if (data.name === 'slidemeister-prizegiving-{{$key}}-comments') {
                        $('input[name="slide[{{$key}}_comments]"]').val(data.definitions_as_form_data);
                        $('input[name="cached_html_preview[{{$key}}_comments]"]').val($('#slidemeister-prizegiving-{{$key}}-comments').html());
                        $('input[name="cached_html_final[{{$key}}_comments]"]').val($('#slidemeister-prizegiving-{{$key}}-comments').html());
                        saveCounter++;
                    }
                    @endif
                    if (data.name === 'slidemeister-prizegiving-{{$key}}-now') {
                        $('input[name="slide[{{$key}}_now]"]').val(data.definitions_as_form_data);
                        $('input[name="cached_html_preview[{{$key}}_now]"]').val($('#slidemeister-prizegiving-{{$key}}-now').html());
                        $('input[name="cached_html_final[{{$key}}_now]"]').val($('#slidemeister-prizegiving-{{$key}}-now').html());
                        saveCounter++;
                    }
                    if (data.name === 'slidemeister-prizegiving-{{$key}}-slide') {
                        $('input[name="slide[{{$key}}_slide]"]').val(data.definitions_as_form_data);
                        $('input[name="cached_html_preview[{{$key}}_slide]"]').val($('#slidemeister-prizegiving-{{$key}}-slide').html());
                        $('input[name="cached_html_final[{{$key}}_slide]"]').val($('#slidemeister-prizegiving-{{$key}}-slide').html());
                        $('input[name="meta[{{$key}}_slide]"]').val(data.meta);
                        saveCounter++;
                    }
                    if (data.name === 'slidemeister-prizegiving-{{$key}}-winners') {
                        $('input[name="slide[{{$key}}_winners]"]').val(data.definitions_as_form_data);
                        $('input[name="cached_html_preview[{{$key}}_winners]"]').val($('#slidemeister-prizegiving-{{$key}}-winners').html());
                        $('input[name="cached_html_final[{{$key}}_winners]"]').val($('#slidemeister-prizegiving-{{$key}}-winners').html());
                        {{--$('input[name="meta[{{$key}}_slide]"]').val(data.definitions.properties.prizegivingbarCoordinates);--}}
                        saveCounter++;
                    }
                    @endforeach
                    @if (count($specialVotes) > 0)
                    if (data.name === 'slidemeister-prizegiving-special-now') {
                        $('input[name="slide[special_now]"]').val(data.definitions_as_form_data);
                        $('input[name="cached_html_preview[special_now]"]').val($('#slidemeister-prizegiving-special-now').html());
                        $('input[name="cached_html_final[special_now]"]').val($('#slidemeister-prizegiving-special-now').html());
                        saveCounter++;
                    }
                    if (data.name === 'slidemeister-prizegiving-special-slide') {
                        $('input[name="slide[special_slide]"]').val(data.definitions_as_form_data);
                        $('input[name="cached_html_preview[special_slide]"]').val($('#slidemeister-prizegiving-special-slide').html());
                        $('input[name="cached_html_final[special_slide]"]').val($('#slidemeister-prizegiving-special-slide').html());
                        $('input[name="meta[special_slide]"]').val(data.meta);
                        saveCounter++;
                    }
                    if (data.name === 'slidemeister-prizegiving-special-winners') {
                        $('input[name="slide[special_winners]"]').val(data.definitions_as_form_data);
                        $('input[name="cached_html_preview[special_winners]"]').val($('#slidemeister-prizegiving-special-winners').html());
                        $('input[name="cached_html_final[special_winners]"]').val($('#slidemeister-prizegiving-special-winners').html());
                        // $('input[name="meta[special_slide]"]').val(data.definitions.properties.prizegivingbarCoordinates);
                        saveCounter++;
                    }
                    @endif
                    Vue.prototype.$eventHub.$emit('partymeister-slides:timetable-finished');
                });

                Vue.prototype.$eventHub.$emit('partymeister-slides:request-definitions', 'slidemeister-prizegiving-comingup');
                Vue.prototype.$eventHub.$emit('partymeister-slides:request-definitions', 'slidemeister-prizegiving-now');
                Vue.prototype.$eventHub.$emit('partymeister-slides:request-definitions', 'slidemeister-prizegiving-end');
                @foreach ($results as $key => $competition)
                Vue.prototype.$eventHub.$emit('partymeister-slides:request-definitions', 'slidemeister-prizegiving-{{$key}}-now');
                Vue.prototype.$eventHub.$emit('partymeister-slides:request-definitions', 'slidemeister-prizegiving-{{$key}}-slide');
                Vue.prototype.$eventHub.$emit('partymeister-slides:request-definitions', 'slidemeister-prizegiving-{{$key}}-winners');
                @if ($competition['has_comment'])
                Vue.prototype.$eventHub.$emit('partymeister-slides:request-definitions', 'slidemeister-prizegiving-{{$key}}-comments');
                @endif
                @endforeach
                @if (count($specialVotes) > 0)
                Vue.prototype.$eventHub.$emit('partymeister-slides:request-definitions', 'slidemeister-prizegiving-special-now');
                Vue.prototype.$eventHub.$emit('partymeister-slides:request-definitions', 'slidemeister-prizegiving-special-slide');
                Vue.prototype.$eventHub.$emit('partymeister-slides:request-definitions', 'slidemeister-prizegiving-special-winners');
                @endif

                e.preventDefault();
            });
        });
    </script>
@append
@endif
