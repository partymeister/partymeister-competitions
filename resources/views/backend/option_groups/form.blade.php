{!! form_start($form) !!}
<div class="@boxWrapper box-primary">
    <div class="@boxHeader with-border">
        <h3 class="box-title">{{ trans('motor-admin::backend/global.base_info') }}</h3>
    </div>
    <div class="@boxBody">
        {!! form_row($form->name) !!}
        {!! form_row($form->type) !!}
        <label class="control-label">{{ trans('partymeister-competitions::backend/option_groups.options') }}</label>
        <div class="options-container" data-prototype="{{ form_row($form->options->prototype()) }}">
            {!! form_row($form->options) !!}
        </div>
        <p>
            <button type="button"
                    class="btn btn-success btn-sm add-to-options">{{ trans('partymeister-competitions::backend/option_groups.add_option') }}</button>
        </p>
    </div>
    <!-- /.box-body -->

    <div class="@boxFooter">
        {!! form_row($form->submit) !!}
    </div>
</div>
{!! form_end($form) !!}
@section('view_scripts')
    <script type="module">
        $(document).ready(function () {

            $(document).on('keypress', 'input.options-name', function(e) {
                /* ENTER PRESSED*/
                if (e.keyCode == 13) {
                    /* FOCUS ELEMENT */
                    $('.add-to-options').click();
                    $('input.options-name:last').focus();
                    return false;
                }
            });

            function findHighestElement(selector) {
                // Get previous index
                let pattern = /\[([0-9]+)\]/;
                let highestElement = 0;
                $(selector).each(function(index, element) {
                    let i = $(element).prop('name').match(pattern);

                    if (i[1] != undefined && parseInt(i[1]) > highestElement) {
                        highestElement = parseInt(i[1]);
                    }
                });
                return highestElement;
            }

            $('.add-to-options').on('click', function (e) {
                e.preventDefault();
                let container = $('.options-container');

                let highestElement = findHighestElement('.options-name');

                let proto = container.data('prototype').replace(/__NAME__/g, highestElement+1);

                container.append(proto);
            });

            $(document).on('click', 'button.remove-options', function (e) {
                e.preventDefault();
                $(this).parent().parent().remove();
            });
        });
    </script>
@append