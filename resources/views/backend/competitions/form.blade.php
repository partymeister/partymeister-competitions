{!! form_start($form) !!}
<div class="@boxWrapper box-primary">
    <div class="@boxHeader with-border">
        <h3 class="box-title">{{ trans('motor-backend::backend/global.base_info') }}</h3>
    </div>
    <div class="@boxBody">
        {!! form_row($form->name) !!}
        {!! form_row($form->competition_type_id) !!}
        {!! form_row($form->option_groups) !!}
        {!! form_row($form->vote_categories) !!}
        {!! form_row($form->sort_position) !!}
        {!! form_row($form->has_prizegiving) !!}
        <div class="has-prizegiving">
            {!! form_row($form->prizegiving_sort_position) !!}
        </div>
        {!! form_row($form->upload_enabled) !!}
        {!! form_row($form->voting_enabled) !!}
    </div>
    <!-- /.box-body -->

</div>
<div class="@boxWrapper box-primary">
    <div class="@boxHeader with-border">
        <h3 class="box-title">{{ trans('partymeister-competitions::backend/competitions.videos') }}</h3>
    </div>
    <div class="@boxBody">
        <div class="row">
            <div class="col-md-4">
                {!! form_row($form->video_1) !!}
            </div>
            <div class="col-md-4">
                {!! form_row($form->video_2) !!}
            </div>
            <div class="col-md-4">
                {!! form_row($form->video_3) !!}
            </div>
        </div>
    </div>
    <!-- /.box-body -->

    <div class="@boxFooter">
        {!! form_row($form->submit) !!}
    </div>
</div>
{!! form_end($form) !!}
@section ('right-sidebar')
    <motor-media-mediapool></motor-media-mediapool>
@endsection
@section('right-sidebar-header')<h5 class="pb-0 mb-0">Media</h5>@endsection

@section('view_scripts')
    <script type="text/javascript">
        $(document).ready(function () {

            $('#has_prizegiving').change(function () {
                if ($(this).prop('checked')) {
                    $('.has-prizegiving').removeClass('hide');
                } else {
                    $('.has-prizegiving').addClass('hide');
                }
            });

            $('#has_prizegiving').change();
        });
    </script>
@append
