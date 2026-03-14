<h4 class="text-lg font-bold mb-4">
    Releases
</h4>
@if (is_null($competition))
    <div class="alert alert-warning mb-4">
        <span>There are no releases yet!</span>
    </div>
@endif
@if (!is_null($competition))
    <h4 class="text-lg font-bold mb-4">{{$competition->name}}</h4>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach ($competition->entries()->where('status', 1)->orderBy('sort_position', 'ASC')->get() as $entry)
            <div>
                <div class="card bg-base-200 shadow-md">
                    @if($entry->getFirstMedia('screenshot'))
                        <figure>
                            <a data-caption="{{$entry->title}} @if (!$entry->competition->competition_type->is_anonymous)by {{$entry->author}}@endif" data-fancybox="gallery"
                               href="{{$entry->getFirstMedia('screenshot')->getUrl('preview')}}">
                                <img src="{{$entry->getFirstMedia('screenshot')->getUrl('preview')}}"
                                     class="w-full">
                            </a>
                        </figure>
                    @endif
                    @if($entry->getFirstMedia('audio'))
                        <audio controls src="{{$entry->getFirstMedia('audio')->getUrl()}}" class="w-full"></audio>
                    @endif
                    <div class="card-body">
                        <h5 class="card-title text-base">{{$entry->title}} @if (!$entry->competition->competition_type->is_anonymous) by {{$entry->author}} @endif</h5>
                        <h6 class="text-sm opacity-70">{{$entry->competition->name}}</h6>
                        @if ($entry->download != null)
                            <a href="{{$entry->download->getUrl()}}" class="btn btn-success btn-sm btn-block mt-2 no-underline">
                                Download
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
