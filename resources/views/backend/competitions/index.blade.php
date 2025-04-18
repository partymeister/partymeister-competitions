@extends('motor-backend::layouts.backend')

@section('htmlheader_title')
    {{ trans('motor-backend::backend/global.home') }}
@endsection

@section('contentheader_title')
    {{ trans('partymeister-competitions::backend/competitions.competitions') }}
    @if (has_permission('competitions.write'))
	    {!! link_to_route('backend.competitions.create', trans('partymeister-competitions::backend/competitions.new'), [], ['class' => 'pull-right float-right btn btn-sm btn-success']) !!}
    @endif
@endsection

@section('main-content')
    <div class="@boxWrapper">
        <div class="@boxHeader">
            @include('motor-backend::layouts.partials.search')
        </div>
        <!-- /.box-header -->
        @if (isset($grid))
            @include('motor-backend::grid.table')
        @endif
    </div>
@endsection

@section('view_scripts')
    <script type="text/javascript">
        $('.delete-record').click(function (e) {
            if (!confirm('{{ trans('motor-backend::backend/global.delete_question') }}')) {
                e.preventDefault();
                return false;
            }
        });

        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        });

        let apiToken = '{{Auth::user()->api_token}}';

        let switchCssClass = function (that, value, cssClass1, cssClass2) {
            if (value == true) {
                $(that).removeClass(cssClass2);
                $(that).addClass(cssClass1);
            } else {
                $(that).removeClass(cssClass1);
                $(that).addClass(cssClass2);
            }
        };

        $('.change-competition-live-voting').click(function (e) {
            e.preventDefault();

            updateRecord(this, $(this).data('record'), {switch_live_voting: $(this).data('live-voting-enabled')}, function (that, results) {
                switchCssClass(that, results.data.live_voting_enabled, $(that).data('class'), $(that).data('class-alternate'));
                $(that).data('live-voting-enabled', results.data.live_voting_enabled ? 0 : 1);
                if (results.data.live_voting_enabled) {
                    toastr.options = {progressBar: true};
                    toastr.success('{{trans('partymeister-competitions::backend/competitions.live_voting_enabled')}}', '{{ trans('motor-backend::backend/global.flash.success') }}');
                } else {
                    toastr.options = {progressBar: true};
                    toastr.success('{{trans('partymeister-competitions::backend/competitions.live_voting_disabled')}}', '{{ trans('motor-backend::backend/global.flash.success') }}');
                }
            });
        });


        $('.change-competition-upload').click(function (e) {
            e.preventDefault();

            updateRecord(this, $(this).data('record'), {upload_enabled: $(this).data('upload-enabled')}, function (that, results) {
                switchCssClass(that, results.data.upload_enabled, $(that).data('class'), $(that).data('class-alternate'));
                $(that).data('upload-enabled', results.data.upload_enabled ? 0 : 1);
                if (results.data.upload_enabled) {
                    toastr.options = {progressBar: true};
                    toastr.success('{{trans('partymeister-competitions::backend/competitions.upload_enabled')}}', '{{ trans('motor-backend::backend/global.flash.success') }}');
                    $(that).parent().find('.change-competition-voting').prop('disabled', true);
                } else {
                    $(that).parent().find('.change-competition-voting').prop('disabled', false);
                    toastr.options = {progressBar: true};
                    toastr.success('{{trans('partymeister-competitions::backend/competitions.upload_disabled')}}', '{{ trans('motor-backend::backend/global.flash.success') }}');
                }
            });
        });

        $('.change-competition-voting').click(function (e) {
            e.preventDefault();

            updateRecord(this, $(this).data('record'), {voting_enabled: $(this).data('voting-enabled')}, function (that, results) {
                switchCssClass(that, results.data.voting_enabled, $(that).data('class'), $(that).data('class-alternate'));
                $(that).data('voting-enabled', results.data.voting_enabled ? 0 : 1);
                if (results.data.voting_enabled) {
                    toastr.options = {progressBar: true};
                    toastr.success('{{trans('partymeister-competitions::backend/competitions.voting_enabled')}}', '{{ trans('motor-backend::backend/global.flash.success') }}');
                    $(that).parent().find('.change-competition-upload').prop('disabled', true);
                } else {
                    toastr.options = {progressBar: true};
                    toastr.success('{{trans('partymeister-competitions::backend/competitions.voting_disabled')}}', '{{ trans('motor-backend::backend/global.flash.success') }}');
                    $(that).parent().find('.change-competition-upload').prop('disabled', false);
                }
            });
        });

        let updateRecord = function (that, recordId, data, callback) {
            $.ajax({
                type: 'PATCH',
                url: '{{action('\Partymeister\Competitions\Http\Controllers\Api\CompetitionsController@index')}}/' + recordId + '?api_token=' + apiToken,
                data: data
            }).done(function (results) {
                callback(that, results);
            });
        };

        $('.change-sort-position').blur(function (e) {
            e.preventDefault();

            let data = {};
            data[$(this).data('field')] = $(this).val();

            updateRecord(this, $(this).data('record'), data, function (that, results) {
                toastr.options = {progressBar: true};
                if ($(that).data('field') === 'sort_position') {
                    toastr.success('{{trans('partymeister-competitions::backend/competitions.sort_position_updated')}}', '{{ trans('motor-backend::backend/global.flash.success') }}');
                } else {
                    toastr.success('{{trans('partymeister-competitions::backend/competitions.prizegiving_sort_position_updated')}}', '{{ trans('motor-backend::backend/global.flash.success') }}');
                }
            });
        });
    </script>
@append
