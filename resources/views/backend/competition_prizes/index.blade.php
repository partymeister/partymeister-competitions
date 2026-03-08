@extends('motor-backend::layouts.backend')

@section('htmlheader_title')
    {{ trans('motor-backend::backend/global.home') }}
@endsection

@section('contentheader_title')
    {{ trans('partymeister-competitions::backend/competition_prizes.competition_prizes') }}
    @if (has_permission('competition_prizes.write'))
	    {!! link_to_route('backend.competition_prizes.create', trans('partymeister-competitions::backend/competition_prizes.new'), [], ['class' => 'float-right btn btn-sm btn-success']) !!}
        {!! link_to_route('backend.competition_prizes.export.receipt', trans('partymeister-competitions::backend/competition_prizes.empty_receipt'), [], ['class' => 'float-right btn btn-sm btn-danger']) !!}
        {!! link_to_route('backend.competition_prizes.export.prizesheet', trans('partymeister-competitions::backend/competition_prizes.prizesheet'), ['prizesheet' => true, 'receipt' => true], ['class' => 'float-right btn btn-sm btn-danger']) !!}
        {!! link_to_route('backend.competition_prizes.export.prizesheet', trans('partymeister-competitions::backend/competition_prizes.prizesheet_only'), ['prizesheet' => true], ['class' => 'float-right btn btn-sm btn-danger']) !!}
        {!! link_to_route('backend.competition_prizes.export.prizesheet', trans('partymeister-competitions::backend/competition_prizes.receipts_only'), ['receipt' => true], ['class' => 'float-right btn btn-sm btn-danger']) !!}
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
    <script type="module">
        $('.delete-record').click(function (e) {
            if (!confirm('{{ trans('motor-backend::backend/global.delete_question') }}')) {
                e.preventDefault();
                return false;
            }
        });
    </script>
@append