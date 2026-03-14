<div class="component-entry-screenshot">
    <h3 class="mb-4">Upload screenshot</h3>
    @include('motor-backend::errors.list')
    {!! form_start($entryScreenshotForm) !!}
    <div class="rounded-lg bg-surface border border-border shadow-[0_4px_12px_rgba(0,0,0,0.4)] mb-4">
        <div class="px-5 py-3 bg-surface-raised rounded-t-lg text-heading font-semibold border-b border-border">
            <h4>Your entry</h4>
        </div>
        <div class="p-5">
            {{$record->title}} by {{$record->author}}
        </div>
    </div>
    <div class="rounded-lg bg-surface border border-border shadow-[0_4px_12px_rgba(0,0,0,0.4)] mb-4">
        <div class="px-5 py-3 bg-surface-raised rounded-t-lg text-heading font-semibold border-b border-border">
            <h4>Screenshot</h4>
        </div>
        <div class="p-5">
            @if ($entryScreenshotForm->has('screenshot'))
                {!! form_row($entryScreenshotForm->screenshot, ['label' => false]) !!}
            @endif
        </div>
        <div class="px-5 pb-5">
            {!! form_row($entryScreenshotForm->submit) !!}
        </div>
    </div>
</div>
{!! form_end($entryScreenshotForm, false) !!}
