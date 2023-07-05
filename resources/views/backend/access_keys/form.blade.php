{!! form_start($form) !!}
<div class="@boxWrapper box-primary">
    <div class="@boxHeader with-border">
        <h3 class="box-title">{{ trans('motor-backend::backend/global.base_info') }}</h3>
    </div>
    <div class="@boxBody">
        {!! form_row($form->access_key) !!}
        {!! form_row($form->ip_address) !!}
        @if (!is_null($form->registered_at->getValue()))
            {!! form_row($form->registered_at) !!}
        @endif
        {!! form_row($form->is_remote) !!}
        {!! form_row($form->is_satellite) !!}
        {!! form_row($form->is_prepaid) !!}
    </div>
    <!-- /.box-body -->

    <div class="@boxFooter">
        {!! form_row($form->submit) !!}
    </div>
</div>
{!! form_end($form, false) !!}
