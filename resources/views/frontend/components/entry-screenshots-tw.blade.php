<div class="component-entry-screenshot">
    <h4 class="text-lg font-bold mb-4">Upload screenshot</h4>
    @include('motor-backend::errors.list')
    {!! form_start($entryScreenshotForm) !!}
    <div class="card bg-base-200 shadow-md mb-4">
        <div class="card-title bg-base-300 p-4">
            <h5 class="text-base font-semibold">Your entry</h5>
        </div>
        <div class="card-body">
            {{$record->title}} by {{$record->author}}
        </div>
    </div>
    <div class="card bg-base-200 shadow-md mb-4">
        <div class="card-title bg-base-300 p-4">
            <h5 class="text-base font-semibold">Screenshot</h5>
        </div>
        <div class="card-body">
            @if ($entryScreenshotForm->has('screenshot'))
                {!! form_row($entryScreenshotForm->screenshot, ['label' => false]) !!}
            @endif
        </div>
        <div class="card-body pt-0">
            {!! form_row($entryScreenshotForm->submit) !!}
        </div>
    </div>
</div>
{!! form_end($entryScreenshotForm, false) !!}
