{!! form_start($form) !!}
<div class="@boxWrapper box-primary">
    <div class="@boxHeader with-border">
        <h3 class="box-title">{{ trans('motor-admin::backend/global.base_info') }}</h3>
    </div>
    <div class="@boxBody">
        {!! form_row($form->name) !!}
        {!! form_row($form->points) !!}
        <div class="row">
            <div class="col-md-4">
                {!! form_row($form->has_negative) !!}
            </div>
            <div class="col-md-4">
                {!! form_row($form->has_comment) !!}
            </div>
            <div class="col-md-4">
                {!! form_row($form->has_special_vote) !!}
            </div>
        </div>
    </div>
    <!-- /.box-body -->

    <div class="@boxFooter">
        {!! form_row($form->submit) !!}
    </div>
</div>
{!! form_end($form) !!}
