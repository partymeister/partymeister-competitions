<div class="component-entry-upload" x-data="entryUploads">
    <h4 class="text-lg font-bold mb-4">Upload entry</h4>
    @include('motor-backend::errors.list')
    {!! form_start($entryUploadForm) !!}
    <div class="card bg-base-200 shadow-md mb-4">
        <div class="card-title bg-base-300 p-4">
            <h3 class="text-base font-semibold">{{ trans('motor-backend::backend/global.base_info') }}</h3>
        </div>
        <div class="card-body">
            {!! form_row($entryUploadForm->reload_on_change) !!}
            @if ($entryUploadForm->getModel())
                {!! form_label($entryUploadForm->competition_id) !!}
                <p>
                    {{$record->competition->name}}
                </p>
            @else
                {!! form_row($entryUploadForm->competition_id) !!}
            @endif
            @if (old($entryUploadForm->getName().'.competition_id') || (isset($entryUploadForm->getModel()[$entryUploadForm->getName()]) && $entryUploadForm->getModel()[$entryUploadForm->getName()]['competition_id'] > 0))
                {!! form_row($entryUploadForm->author) !!}
                {!! form_row($entryUploadForm->title) !!}
            @endif
        </div>
    </div>
    @if (old($entryUploadForm->getName().'.competition_id') || (isset($entryUploadForm->getModel()[$entryUploadForm->getName()]) && $entryUploadForm->getModel()[$entryUploadForm->getName()]['competition_id'] > 0))
        <div class="card bg-base-200 shadow-md mb-4">
            <div class="card-title bg-base-300 p-4">
                <h3 class="text-base font-semibold">{{ trans('partymeister-competitions::backend/entries.entry_info') }}</h3>
            </div>
            <div class="card-body">
                {!! form_row($entryUploadForm->description) !!}
                {!! form_row($entryUploadForm->organizer_description) !!}
                {!! form_row($entryUploadForm->discord_name) !!}
                @if ($entryUploadForm->has('running_time'))
                    {!! form_row($entryUploadForm->running_time) !!}
                @endif
            </div>
        </div>
        <div class="card bg-base-200 shadow-md mb-4">
            <div class="card-title bg-base-300 p-4">
                <h3 class="text-base font-semibold">{{ trans('partymeister-competitions::backend/entries.option_info') }}</h3>
            </div>
            <div class="card-body">
                {!! form_row($entryUploadForm->options) !!}
                {!! form_row($entryUploadForm->custom_option) !!}
            </div>
        </div>

        <div class="card bg-base-200 shadow-md mb-4">
            <div class="card-title bg-base-300 p-4">
                <h3 class="text-base font-semibold">{{ trans('partymeister-competitions::backend/entries.file_info') }}</h3>
            </div>
            <div class="card-body">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    @if ($entryUploadForm->has('screenshot'))
                        <div>
                            {!! form_row($entryUploadForm->screenshot) !!}
                        </div>
                    @endif
                    @if ($entryUploadForm->has('audio'))
                        <div>
                            {!! form_row($entryUploadForm->audio) !!}
                        </div>
                    @endif
                    @if ($entryUploadForm->has('video'))
                        <div>
                            {!! form_row($entryUploadForm->video) !!}
                        </div>
                    @endif
                        @if ($entryUploadForm->has('config_file'))
                            <div>
                                {!! form_row($entryUploadForm->config_file) !!}
                            </div>
                        @endif
                </div>

                @if ($entryUploadForm->has('work_stage_1'))
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">

                        @php
                            $i = 1;
                        @endphp
                        @while ($entryUploadForm->has('work_stage_'.$i))
                            <div>
                                {!! form_row($entryUploadForm->{'work_stage_'.$i}) !!}
                            </div>
                            @php
                                $i++;
                            @endphp
                        @endwhile
                    </div>
                @endif

                {!! form_row($entryUploadForm->file) !!}
            </div>
        </div>

        @if ($entryUploadForm->has('ai_usage'))
            <div class="card bg-base-200 shadow-md mb-4">
                <div class="card-title bg-base-300 p-4">
                    <h3 class="text-base font-semibold">{{ trans('partymeister-competitions::backend/entries.ai_information') }}</h3>
                </div>
                <div class="card-body">
                    {!! form_row($entryUploadForm->ai_usage) !!}
                    {!! form_row($entryUploadForm->ai_usage_description) !!}
                </div>
            </div>
        @endif


        @if ($entryUploadForm->has('engine_option'))
            <div class="card bg-base-200 shadow-md mb-4">
                <div class="card-title bg-base-300 p-4">
                    <h3 class="text-base font-semibold">{{ trans('partymeister-competitions::backend/entries.engine_information') }}</h3>
                </div>
                <div class="card-body">
                    {!! form_row($entryUploadForm->engine_option) !!}
                    {!! form_row($entryUploadForm->engine_option_description) !!}
                    {!! form_row($entryUploadForm->engine_creator_involvement) !!}
                </div>
            </div>
        @endif


        <div class="card bg-base-200 shadow-md mb-4">
            <div class="card-title bg-base-300 p-4">
                <h3 class="text-base font-semibold">{{ trans('partymeister-competitions::backend/entries.author_info') }}</h3>
            </div>
            <div class="card-body">
                {!! form_row($entryUploadForm->author_name) !!}
                {!! form_row($entryUploadForm->author_email) !!}
                {!! form_row($entryUploadForm->author_phone) !!}
                {!! form_row($entryUploadForm->author_address) !!}
                {!! form_row($entryUploadForm->author_zip) !!}
                {!! form_row($entryUploadForm->author_city) !!}
                {!! form_row($entryUploadForm->author_country_iso_3166_1) !!}
            </div>
        </div>
        @if ($entryUploadForm->has('composer_name'))
            <div class="card bg-base-200 shadow-md mb-4">
                <div class="card-title bg-base-300 p-4">
                    <h3 class="text-base font-semibold flex items-center justify-between">
                        <span>{{ trans('partymeister-competitions::backend/entries.composer_info') }}</span>
                        <button type="button" class="btn btn-success btn-sm" x-on:click="copyAuthorToComposer()">Copy author data</button>
                    </h3>
                </div>
                <div class="card-body">
                    {!! form_row($entryUploadForm->composer_name) !!}
                    {!! form_row($entryUploadForm->composer_email) !!}
                    {!! form_row($entryUploadForm->composer_phone) !!}
                    {!! form_row($entryUploadForm->composer_address) !!}
                    {!! form_row($entryUploadForm->composer_zip) !!}
                    {!! form_row($entryUploadForm->composer_city) !!}
                    {!! form_row($entryUploadForm->composer_country_iso_3166_1) !!}
                </div>
            </div>
        @endif
        <div class="card bg-base-200 shadow-md mb-4">
            <div class="card-title bg-base-300 p-4">
                <h3 class="text-base font-semibold">{{ trans('partymeister-competitions::backend/entries.notify_about_status') }}</h3>
            </div>
            <div class="card-body">
                <p class="mb-2">
                    Be aware that we have competitions which involve jury preselection, mostly - but not limited to - individual competitions such as music or graphics.
                </p>
                <p class="mb-4">
                    In case that your entry misses preselection, you can opt-in to receive an email with that information directly before the competition is shown.
                </p>

                {!! form_row($entryUploadForm->notify_about_status) !!}
            </div>
            @if ($visitor->is_remote)
            <div class="card-title bg-base-300 p-4">
                <h3 class="text-base font-semibold">{{ trans('partymeister-competitions::backend/entries.remote_participation') }}</h3>
            </div>
            <div class="card-body">
                <p class="mb-2">
                    In order to avoid having an empty stage in case your entry ranks among the top three of this competition, we ask you kindly to name a
                    representative at the party location. This can be a group member, a friend or a kind soul that accepts the prize in your stead.
                </p>
                <p class="mb-4">
                    If you have no way of having somebody represent you, please enter "organizer". That way, we know that we don't have to wait for somebody
                    to show up on stage <3
                </p>
                {!! form_row($entryUploadForm->representative) !!}
            </div>
            @endif
        </div>
        <div class="card bg-base-200 shadow-md mb-4">
            <div class="card-body">
                {!! form_row($entryUploadForm->submit) !!}
            </div>
        </div>
    @endif
    {!! form_end($entryUploadForm, false) !!}
</div>
@section('view-scripts')
    <script type="module">
        document.addEventListener('alpine:init', () => {
            Alpine.data('entryUploads', () => ({
                init() {
                    // Prevent form submit on Enter key
                    this.$el.querySelectorAll('input').forEach(input => {
                        input.addEventListener('keypress', (e) => {
                            if (e.which === 13) {
                                e.preventDefault();
                            }
                        });
                    });
                },
                reloadOnChange(event) {
                    document.getElementById('reload_on_change').value = 1;
                    event.target.closest('form').submit();
                },
                copyAuthorToComposer() {
                    const fields = ['name', 'email', 'phone', 'address', 'zip', 'city', 'country_iso_3166_1'];
                    fields.forEach(field => {
                        const authorInput = document.querySelector(`input[name="entry-upload[composer_${field}]"]`);
                        const sourceInput = document.querySelector(`input[name="entry-upload[author_${field}]"]`);
                        if (authorInput && sourceInput) {
                            authorInput.value = sourceInput.value;
                        }
                    });
                }
            }));
        });
    </script>
@append
