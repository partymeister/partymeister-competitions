<div class="component-entry-upload" x-data="entryUploads">
    <h3 class="mb-4">Upload entry</h3>
    @if ($errors->any())
        <div class="rounded-lg border border-error/40 border-l-4 border-l-error bg-error/15 px-4 py-3 text-error mb-4">
            <h4 class="mb-2">Please fix the following errors:</h4>
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    {!! form_start($entryUploadForm) !!}
    <div class="rounded-lg bg-surface border border-border shadow-[0_4px_12px_rgba(0,0,0,0.4)] mb-4">
        <div class="px-5 py-3 bg-surface-raised rounded-t-lg text-heading font-semibold border-b border-border">
            <h4>{{ trans('motor-admin::backend/global.base_info') }}</h4>
        </div>
        <div class="p-5">
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
        <div class="rounded-lg bg-surface border border-border shadow-[0_4px_12px_rgba(0,0,0,0.4)] mb-4">
            <div class="px-5 py-3 bg-surface-raised rounded-t-lg text-heading font-semibold border-b border-border">
                <h4>{{ trans('partymeister-competitions::backend/entries.entry_info') }}</h4>
            </div>
            <div class="p-5">
                {!! form_row($entryUploadForm->description) !!}
                {!! form_row($entryUploadForm->organizer_description) !!}
                {!! form_row($entryUploadForm->discord_name) !!}
                @if ($entryUploadForm->has('running_time'))
                    {!! form_row($entryUploadForm->running_time) !!}
                @endif
            </div>
        </div>
        <div class="rounded-lg bg-surface border border-border shadow-[0_4px_12px_rgba(0,0,0,0.4)] mb-4">
            <div class="px-5 py-3 bg-surface-raised rounded-t-lg text-heading font-semibold border-b border-border">
                <h4>{{ trans('partymeister-competitions::backend/entries.option_info') }}</h4>
            </div>
            <div class="p-5">
                {!! form_row($entryUploadForm->options) !!}
                {!! form_row($entryUploadForm->custom_option) !!}
            </div>
        </div>

        <div class="rounded-lg bg-surface border border-border shadow-[0_4px_12px_rgba(0,0,0,0.4)] mb-4">
            <div class="px-5 py-3 bg-surface-raised rounded-t-lg text-heading font-semibold border-b border-border">
                <h4>{{ trans('partymeister-competitions::backend/entries.file_info') }}</h4>
            </div>
            <div class="p-5">
                @if ($entryUploadForm->has('screenshot'))
                    {!! form_row($entryUploadForm->screenshot) !!}
                @endif
                @if ($entryUploadForm->has('audio'))
                    {!! form_row($entryUploadForm->audio) !!}
                @endif
                @if ($entryUploadForm->has('video'))
                    {!! form_row($entryUploadForm->video) !!}
                @endif
                @if ($entryUploadForm->has('config_file'))
                    {!! form_row($entryUploadForm->config_file) !!}
                @endif

                @if ($entryUploadForm->has('work_stage_1'))
                    @php $i = 1; @endphp
                    @while ($entryUploadForm->has('work_stage_'.$i))
                        {!! form_row($entryUploadForm->{'work_stage_'.$i}) !!}
                        @php $i++; @endphp
                    @endwhile
                @endif

                {!! form_row($entryUploadForm->file) !!}
            </div>
        </div>

        @if ($entryUploadForm->has('engine_option'))
            <div class="rounded-lg bg-surface border border-border shadow-[0_4px_12px_rgba(0,0,0,0.4)] mb-4"
                 x-data="{ engineOption: '{{ old('entry-upload.engine_option', '') }}' }">
                <div class="px-5 py-3 bg-surface-raised rounded-t-lg text-heading font-semibold border-b border-border">
                    <h4>{{ trans('partymeister-competitions::backend/entries.engine_information') }}</h4>
                </div>
                <div class="p-5">
                    <div x-init="
                        const sel = $el.parentElement.querySelector('select[name=\'entry-upload[engine_option]\']');
                        if (sel) { engineOption = sel.value; sel.addEventListener('change', e => engineOption = e.target.value); }
                    ">
                        {!! form_row($entryUploadForm->engine_option) !!}
                    </div>
                    <div x-show="engineOption === 'other'" x-cloak>
                        {!! form_row($entryUploadForm->engine_option_description) !!}
                    </div>
                </div>
            </div>
        @endif


        <div class="rounded-lg bg-surface border border-border shadow-[0_4px_12px_rgba(0,0,0,0.4)] mb-4">
            <div class="px-5 py-3 bg-surface-raised rounded-t-lg text-heading font-semibold border-b border-border">
                <h4>{{ trans('partymeister-competitions::backend/entries.author_info') }}</h4>
            </div>
            <div class="p-5">
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
            <div class="rounded-lg bg-surface border border-border shadow-[0_4px_12px_rgba(0,0,0,0.4)] mb-4">
                <div class="px-5 py-3 bg-surface-raised rounded-t-lg text-heading font-semibold border-b border-border">
                    <h4 class="flex items-center justify-between">
                        <span>{{ trans('partymeister-competitions::backend/entries.composer_info') }}</span>
                        <button type="button" class="inline-flex items-center justify-center rounded-lg bg-accent px-3 py-1.5 text-sm font-medium text-body hover:bg-accent-hover transition-colors" x-on:click="copyAuthorToComposer()">Copy author data</button>
                    </h4>
                </div>
                <div class="p-5">
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
        <div class="rounded-lg bg-surface border border-border shadow-[0_4px_12px_rgba(0,0,0,0.4)] mb-4">
            <div class="px-5 py-3 bg-surface-raised rounded-t-lg text-heading font-semibold border-b border-border">
                <h4>{{ trans('partymeister-competitions::backend/entries.notify_about_status') }}</h4>
            </div>
            <div class="p-5">
                <p class="mb-2">
                    Be aware that we have competitions which involve jury preselection, mostly - but not limited to - individual competitions such as music or graphics.
                </p>
                <p class="mb-4">
                    In case that your entry misses preselection, you can opt-in to receive an email with that information directly before the competition is shown.
                </p>

                {!! form_row($entryUploadForm->notify_about_status) !!}
            </div>
            @if ($visitor->is_remote)
            <div class="px-5 py-3 bg-surface-raised rounded-t-lg text-heading font-semibold border-b border-border">
                <h4>{{ trans('partymeister-competitions::backend/entries.remote_participation') }}</h4>
            </div>
            <div class="p-5">
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
        <div class="rounded-lg bg-surface border border-border shadow-[0_4px_12px_rgba(0,0,0,0.4)] mb-4 border-l-4 border-l-warning">
            <div class="px-5 py-3 bg-surface-raised rounded-t-lg text-heading font-semibold border-b border-border">
                <h4>Confirmation</h4>
            </div>
            <div class="p-5 space-y-3">
                {!! form_row($entryUploadForm->confirm_no_genai) !!}
                {!! form_row($entryUploadForm->confirm_rules) !!}
            </div>
        </div>
        <div class="rounded-lg bg-surface border border-border shadow-[0_4px_12px_rgba(0,0,0,0.4)] mb-4">
            <div class="p-5">
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
                    // Reset reload_on_change so normal form submits work
                    const reloadField = document.getElementById('reload_on_change');
                    if (reloadField) reloadField.value = '';

                    // Prevent form submit on Enter key
                    this.$el.querySelectorAll('input').forEach(input => {
                        input.addEventListener('keypress', (e) => {
                            if (e.which === 13) {
                                e.preventDefault();
                            }
                        });
                    });

                    // Auto-reload when competition is selected
                    const competitionSelect = this.$el.querySelector('select[name="entry-upload[competition_id]"]');
                    if (competitionSelect) {
                        competitionSelect.addEventListener('change', (e) => {
                            this.reloadOnChange(e);
                        });
                    }
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
