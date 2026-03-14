<h4 class="text-lg font-bold mb-4">
    Releases
</h4>
@if (is_null($competition))
    <div class="rounded-lg border border-accent/30 bg-accent/10 px-4 py-3 text-sm text-accent mb-4">
        <span>There are no releases yet!</span>
    </div>
@endif
@if (!is_null($competition))
    <h4 class="text-lg font-bold mb-4">{{$competition->name}}</h4>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach ($competition->entries()->where('status', 1)->orderBy('sort_position', 'ASC')->get() as $entry)
            <div>
                <div class="rounded-lg bg-surface shadow-[0_2px_8px_rgba(0,0,0,0.3)]">
                    @if($entry->getFirstMedia('screenshot'))
                        <figure>
                            <a data-caption="{{$entry->title}} @if (!$entry->competition->competition_type->is_anonymous)by {{$entry->author}}@endif" data-fancybox="gallery"
                               href="{{$entry->getFirstMedia('screenshot')->getUrl('preview')}}">
                                <img src="{{$entry->getFirstMedia('screenshot')->getUrl('preview')}}"
                                     class="w-full rounded-t-lg">
                            </a>
                        </figure>
                    @endif
                    @if($entry->getFirstMedia('audio'))
                        <audio controls src="{{$entry->getFirstMedia('audio')->getUrl()}}" class="w-full"></audio>
                    @endif
                    <div class="p-5">
                        <h5 class="text-heading font-semibold text-base mb-3">{{$entry->title}} @if (!$entry->competition->competition_type->is_anonymous) by {{$entry->author}} @endif</h5>
                        <h6 class="text-sm opacity-70">{{$entry->competition->name}}</h6>
                        @if ($entry->download != null)
                            <a href="{{$entry->download->getUrl()}}" class="w-full inline-flex items-center justify-center rounded-lg bg-success px-3 py-1.5 text-xs font-semibold text-body hover:bg-success/90 transition-colors mt-2 no-underline">
                                Download
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
