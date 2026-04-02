<h3 class="mb-4">
    Releases
</h3>
@if (is_null($competition))
    <div class="rounded-lg border border-accent/40 border-l-4 border-l-accent bg-accent/15 px-4 py-3 text-accent mb-4">
        <span>There are no releases yet!</span>
    </div>
@endif
@if (!is_null($competition))
    <svg xmlns="http://www.w3.org/2000/svg" class="hidden">
        <symbol id="icon-satellite" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M13 7 9 3 5 7l4 4"/><path d="m17 11 4 4-4 4-4-4"/><path d="m8 12 4 4 6-6-4-4Z"/><path d="m16 8 3-3"/><path d="M9 21a6 6 0 0 0-6-6"/>
        </symbol>
        <symbol id="icon-remote" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect width="18" height="12" x="3" y="4" rx="2" ry="2"/><line x1="2" x2="22" y1="20" y2="20"/>
        </symbol>
    </svg>
    <h4 class="mb-4">{{$competition->name}}</h4>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-stretch">
        @foreach ($entries as $entry)
            <div class="flex">
                <div class="flex-1 flex flex-col rounded-lg bg-surface border border-border shadow-[0_4px_12px_rgba(0,0,0,0.4)]">
                    @if($entry->getFirstMedia('screenshot'))
                        <figure>
                            <a data-caption="{{$entry->title}} @if (!$entry->competition->competition_type->is_anonymous && !$entry->hide_author)by {{$entry->author}}@endif" data-fancybox="gallery"
                               href="{{$entry->getFirstMedia('screenshot')->getUrl('preview')}}" class="hover:opacity-90 transition-opacity">
                                <img src="{{$entry->getFirstMedia('screenshot')->getUrl('preview')}}"
                                     alt="Screenshot for {{ $entry->title }}" class="w-full rounded-t-lg">
                            </a>
                        </figure>
                    @endif
                    @if($entry->getFirstMedia('audio'))
                        <audio controls src="{{$entry->getFirstMedia('audio')->getUrl()}}" class="w-full"></audio>
                    @endif
                    <div class="p-5 flex-1 flex flex-col break-words">
                        <h5 class="mb-3">@if ($entry->remote_type)<svg class="w-5 h-5 text-white float-right ml-2 mt-1" role="img" aria-label="{{ $entry->remote_type }}"><title>{{ $entry->remote_type }}</title><use href="#icon-{{ $entry->remote_type == 'Satellite' ? 'satellite' : 'remote' }}"/></svg>@endif{{$entry->title}} @if (!$entry->competition->competition_type->is_anonymous && !$entry->hide_author) by {{$entry->author}} @endif</h5>
                        <h6 class="text-text-muted">{{$entry->competition->name}}</h6>
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
                        @if ($entry->description != '')
                            <h6 class="mt-2">Description</h6>
                            <p class="mt-1">{!! nl2br(e($entry->description)) !!}</p>
                        @endif
                        <div class="mt-auto pt-3"></div>
                        @if ($entry->download != null)
                            <a href="{{$entry->download->getUrl()}}" class="w-full inline-flex items-center justify-center rounded-lg bg-accent px-3 py-1.5 text-sm font-medium text-body hover:bg-accent-hover transition-colors no-underline">
                                Download
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
