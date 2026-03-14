<h4 class="text-lg font-bold mb-4 flex items-center justify-between">
    <span>Your entries</span>
    <a href="{{route('frontend.pages.index', ['slug' => $component->entry_edit_page->full_slug])}}" class="btn btn-success btn-sm">Upload entry</a>
</h4>
@if ($entries->count() == 0)
    <div class="alert alert-warning">
        <span>You haven't uploaded any entries yet!</span>
    </div>
@endif
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    @foreach ($entries as $entry)
        <div class="card bg-base-200 shadow-md">
            @if($entry->getFirstMedia('screenshot'))
                <figure>
                    <a data-caption="{{$entry->title}} @if (!$entry->competition->competition_type->is_anonymous) by {{$entry->author}} @endif" data-fancybox="gallery"
                       href="{{$entry->getFirstMedia('screenshot')->getUrl('preview')}}">
                        <img src="{{$entry->getFirstMedia('screenshot')->getUrl('preview')}}" class="w-full">
                    </a>
                </figure>
            @endif
            <div class="card-body flex flex-col">
                <div class="flex-grow">
                    <h5 class="card-title text-base">{{$entry->title}}@if (!$entry->competition->competition_type->is_anonymous) by {{$entry->author}}@endif</h5>
                    <h6 class="text-sm opacity-70">{{$entry->competition->name}}</h6>
                    @if ($entry->options->count() > 0 || $entry->custom_option != '')
                        <h6 class="mt-2 text-sm font-semibold">Options</h6>
                        <ul class="list-disc list-inside text-sm">
                            @foreach ($entry->options as $option)
                                <li>{{$option->name}}</li>
                            @endforeach
                            @if($entry->custom_option != '')
                                <li>{{$entry->custom_option}}</li>
                            @endif
                        </ul>
                    @endif
                    <p class="text-sm mt-2">{{$entry->description}}</p>
                </div>
                <div class="mt-4">
                    <div class="join join-horizontal w-full">
                        @if ($entry->competition->upload_enabled || $entry->upload_enabled)
                            <a href="{{route('frontend.pages.index', ['slug' => $component->entry_edit_page->full_slug]) }}?entry_id={{$entry->id}}"
                               class="btn btn-primary btn-sm join-item flex-1">Edit</a>
                        @endif
                        @if ($entry->competition->competition_type->has_screenshot)
                            <a href="{{route('frontend.pages.index', ['slug' => $component->entry_screenshots_page->full_slug])}}?entry_id={{$entry->id}}"
                               class="btn btn-primary btn-sm join-item flex-1">Update screenshot</a>
                        @endif
                        <a href="{{route('frontend.pages.index', ['slug' => $component->entry_detail_page->full_slug])}}?entry_id={{$entry->id}}"
                           class="btn btn-primary btn-sm join-item flex-1">Show</a>
                    </div>
                    <a href="{{route('frontend.pages.index', ['slug' => $component->entry_comments_page->full_slug])}}?entry_id={{$entry->id}}"
                       class="btn btn-sm btn-block mt-2 @if ($entry->new_comments > 0) btn-warning @else btn-neutral @endif">Messages @if ($entry->new_comments > 0)
                            ({{$entry->new_comments}} NEW) @endif</a>
                </div>
            </div>
        </div>
    @endforeach
</div>
