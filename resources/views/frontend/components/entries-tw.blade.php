<h3 class="mb-4 flex items-center justify-between">
    <span>Your entries</span>
    <a href="{{route('frontend.pages.index', ['slug' => $component->entry_edit_page->full_slug])}}" class="inline-flex items-center justify-center rounded-lg bg-success px-3 py-1.5 text-sm font-medium text-body hover:bg-success/90 transition-colors">Upload entry</a>
</h3>
@if ($entries->count() == 0)
    <div class="rounded-lg border border-accent/40 border-l-4 border-l-accent bg-accent/15 px-4 py-3 text-accent">
        <span>You haven't uploaded any entries yet!</span>
    </div>
@endif
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    @foreach ($entries as $entry)
        <div class="rounded-lg bg-surface border border-border shadow-[0_4px_12px_rgba(0,0,0,0.4)]">
            @if($entry->getFirstMedia('screenshot'))
                <figure>
                    <a data-caption="{{$entry->title}} @if (!$entry->competition->competition_type->is_anonymous) by {{$entry->author}} @endif" data-fancybox="gallery"
                       href="{{$entry->getFirstMedia('screenshot')->getUrl('preview')}}">
                        <img src="{{$entry->getFirstMedia('screenshot')->getUrl('preview')}}" class="w-full rounded-t-lg">
                    </a>
                </figure>
            @endif
            <div class="p-5 flex flex-col">
                <div class="flex-grow">
                    <h5 class="mb-3">{{$entry->title}}@if (!$entry->competition->competition_type->is_anonymous) by {{$entry->author}}@endif</h5>
                    <h6 class="opacity-70">{{$entry->competition->name}}</h6>
                    @if ($entry->options->count() > 0 || $entry->custom_option != '')
                        <h6 class="mt-2">Options</h6>
                        <ul class="list-disc list-inside">
                            @foreach ($entry->options as $option)
                                <li>{{$option->name}}</li>
                            @endforeach
                            @if($entry->custom_option != '')
                                <li>{{$entry->custom_option}}</li>
                            @endif
                        </ul>
                    @endif
                    <p class="mt-2">{{$entry->description}}</p>
                </div>
                <div class="mt-4">
                    <div class="flex w-full">
                        @if ($entry->competition->upload_enabled || $entry->upload_enabled)
                            <a href="{{route('frontend.pages.index', ['slug' => $component->entry_edit_page->full_slug]) }}?entry_id={{$entry->id}}"
                               class="flex-1 inline-flex items-center justify-center bg-accent px-3 py-1.5 text-sm font-medium text-body hover:bg-accent-hover transition-colors rounded-l-lg">Edit</a>
                        @endif
                        @if ($entry->competition->competition_type->has_screenshot)
                            <a href="{{route('frontend.pages.index', ['slug' => $component->entry_screenshots_page->full_slug])}}?entry_id={{$entry->id}}"
                               class="flex-1 inline-flex items-center justify-center bg-accent px-3 py-1.5 text-sm font-medium text-body hover:bg-accent-hover transition-colors">Update screenshot</a>
                        @endif
                        <a href="{{route('frontend.pages.index', ['slug' => $component->entry_detail_page->full_slug])}}?entry_id={{$entry->id}}"
                           class="flex-1 inline-flex items-center justify-center bg-accent px-3 py-1.5 text-sm font-medium text-body hover:bg-accent-hover transition-colors rounded-r-lg">Show</a>
                    </div>
                    <a href="{{route('frontend.pages.index', ['slug' => $component->entry_comments_page->full_slug])}}?entry_id={{$entry->id}}"
                       class="w-full inline-flex items-center justify-center rounded-lg px-3 py-1.5 text-sm font-medium text-body transition-colors mt-2 @if ($entry->new_comments > 0) bg-accent hover:bg-accent-hover @else bg-surface-raised font-medium text-text hover:text-heading @endif">Messages @if ($entry->new_comments > 0)
                            ({{$entry->new_comments}} NEW) @endif</a>
                </div>
            </div>
        </div>
    @endforeach
</div>
