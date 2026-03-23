<?php if ($showLabel && $showField): ?>
<?php if ($options['wrapper'] !== false): ?>
<div <?= $options['wrapperAttrs'] ?> >
<?php endif; ?>
<?php endif; ?>

<?php if ($showLabel && $options['label'] !== false): ?>
    <?= Form::label($name, $options['label'], $options['label_attr']) ?>
<?php endif; ?>

@foreach ($options['files'] as $file)
    {!! Form::hidden('delete_media_'.$file['id']) !!}
    <div class="media-{{ $file['id'] }}-container mb-2" x-data="{ deleted: false }" x-show="!deleted">
        <div class="flex items-center gap-3">
            <button type="button"
                    class="inline-flex items-center justify-center rounded-lg bg-error px-2.5 py-1.5 text-sm font-medium text-white hover:bg-error/80 transition-colors cursor-pointer shrink-0"
                    x-on:click="if(confirm('{{ trans('motor-admin::backend/global.delete_question') }}')) { deleted = true; document.querySelector('input[name=\'delete_media_{{ $file['id'] }}\']').value = 1; }">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
            </button>
            <div>
                <span class="font-semibold text-heading">{{ $file['name'] }}</span>
                <span class="block text-sm text-text-muted">{{ trans('motor-admin::backend/global.uploaded') }} {{ $file['created_at'] }}</span>
            </div>
        </div>
    </div>
@endforeach

<?php if ($showField): ?>
    <?= Form::input('file', $name, $options['value'], array_merge($options['attr'], ['class' => ''])) ?>
    @include('laravel-form-builder::help_block')
<?php endif; ?>

@include('laravel-form-builder::errors')

<?php if ($showLabel && $showField): ?>
<?php if ($options['wrapper'] !== false): ?>
</div>
<?php endif; ?>
<?php endif; ?>
