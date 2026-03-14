<h3 class="mb-4">
    Releases
</h3>
@if (is_null($competition))
    <div class="rounded-lg border border-accent/40 border-l-4 border-l-accent bg-accent/15 px-4 py-3 text-accent mb-4">
        <span>There are no releases yet!</span>
    </div>
@endif
@if (!is_null($competition))
    <h4 class="mb-4">{{$competition->name}}</h4>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-stretch">
        @foreach ($entries as $entry)
            <div class="flex">
                <div class="flex-1 flex flex-col rounded-lg bg-surface border border-border shadow-[0_4px_12px_rgba(0,0,0,0.4)]">
                    @if($entry->getFirstMedia('screenshot'))
                        <figure>
                            <a data-caption="{{$entry->title}} @if (!$entry->competition->competition_type->is_anonymous)by {{$entry->author}}@endif" data-fancybox="gallery"
                               href="{{$entry->getFirstMedia('screenshot')->getUrl('preview')}}" class="hover:opacity-90 transition-opacity">
                                <img src="{{$entry->getFirstMedia('screenshot')->getUrl('preview')}}"
                                     alt="Screenshot for {{ $entry->title }}" class="w-full rounded-t-lg">
                            </a>
                        </figure>
                    @endif
                    @if($entry->getFirstMedia('audio'))
                        <audio controls src="{{$entry->getFirstMedia('audio')->getUrl()}}" class="w-full"></audio>
                    @endif
                    <div class="p-5 flex-1 flex flex-col">
                        <h5 class="mb-3">{{$entry->title}} @if (!$entry->competition->competition_type->is_anonymous) by {{$entry->author}} @endif</h5>
                        <h6 class="text-text-muted mb-auto">{{$entry->competition->name}}</h6>
                        @if ($entry->download != null)
                            <a href="{{$entry->download->getUrl()}}" class="w-full inline-flex items-center justify-center rounded-lg bg-accent px-3 py-1.5 text-sm font-medium text-body hover:bg-accent-hover transition-colors mt-2 no-underline">
                                Download
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
