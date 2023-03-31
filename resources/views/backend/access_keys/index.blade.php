@extends('motor-backend::layouts.backend')

@section('htmlheader_title')
    {{ trans('motor-backend::backend/global.home') }}
@endsection

@section('contentheader_title')
    {{ trans('partymeister-competitions::backend/access_keys.access_keys') }}
    @if (has_permission('access_keys.write'))
        {!! link_to_route('backend.access_keys.create', trans('partymeister-competitions::backend/access_keys.new'), [], ['class' => 'float-right btn btn-sm btn-success']) !!}
        <button type="button"
                class="btn btn-sm btn-danger float-right access-keys-generate">{{trans('partymeister-competitions::backend/access_keys.generate')}}</button>

        <div class="dropdown float-right">
            <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                {{trans('partymeister-competitions::backend/competition_prizes.downloads')}}
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                {!! link_to_route('backend.access_keys.export.pdf', trans('partymeister-competitions::backend/access_keys.export_pdf'), ['per_page' => 5000], ['class' => 'dropdown-item']) !!}
                {!! link_to_route('backend.access_keys.export.csv', trans('partymeister-competitions::backend/access_keys.export_csv'), ['per_page' => 5000], ['class' => 'dropdown-item']) !!}
            </div>
        </div>


        <div class="loader loader-default" data-text="&hearts; {{trans('partymeister-competitions::backend/access_keys.generating')}} &hearts;"></div>
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

        $('.access-keys-generate').click(function (e) {
            e.preventDefault();
            let quantity = prompt("{{trans('partymeister-competitions::backend/access_keys.delete_and_ask_for_quantity')}}", "500");
            if (quantity != null) {
                $('.loader').addClass('is-active');
                axios.post('{{route('ajax.access_keys.generate')}}', {quantity: quantity}).then(function(response) {
                    location.reload();
                });
            }
        });
    </script>
@append
