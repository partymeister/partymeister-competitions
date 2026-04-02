<h3 class="mb-4">
    Voting
</h3>
@if ($votingDeadlineOver)
    <div class="rounded-lg border border-accent/40 border-l-4 border-l-accent bg-accent/15 px-4 py-3 text-accent mb-4">
        <span>Voting deadline is over!</span>
    </div>
@endif
@if ($liveVoting)
    <div class="rounded-lg border border-success/40 border-l-4 border-l-success bg-success/15 px-4 py-3 text-success mb-4">
        <a class="text-success font-bold underline" href="{{ route('frontend.pages.index', ['slug' => $component->live_voting_page->full_slug])}}">
            Live voting for the {{$liveVotingCompetition}} is active now!
            <strong>Go vote!</strong></a>
    </div>
@endif
@if (is_null($competition) && $liveVoting == false)
    <div class="rounded-lg border border-accent/40 border-l-4 border-l-accent bg-accent/15 px-4 py-3 text-accent mb-4">
        <span>There are no entries to vote for yet!</span>
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
    <form id="save-votes" action="{{ route('frontend.pages.index', ['slug' => 'voting'])}}?competition_id={{$competition->id}}"
          method="post"
          x-data="votingForm({{ $competition->id }}, '{{ url('/api/v2/profile/votes') }}', {{ $votingDeadlineOver ? 'true' : 'false' }})">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <h4 class="mb-4">{{$competition->name}}</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 entries items-stretch">
            @foreach ($entries as $entry)
                <div class="flex">
                    <div class="flex-1 flex flex-col rounded-lg bg-surface border border-border shadow-[0_4px_12px_rgba(0,0,0,0.4)]"
                         x-bind:class="{ 'ring-2 ring-accent': specialVoteEntryId === {{ $entry->id }} }"
                         data-entry-id="{{$entry->id}}">
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
                            <h5 class="mb-3">@if ($entry->remote_type)<svg class="w-5 h-5 text-white float-right ml-2 mt-1" role="img" aria-label="{{ $entry->remote_type }}"><title>{{ $entry->remote_type }}</title><use href="#icon-{{ $entry->remote_type == 'Satellite' ? 'satellite' : 'remote' }}"/></svg>@endif{{$entry->title}} @if (!$entry->competition->competition_type->is_anonymous && !$entry->hide_author)by {{$entry->author}}@endif</h5>
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
                                <h6>Description</h6>
                                <p class="mt-1">{!! nl2br(e($entry->description)) !!}</p>
                            @endif
                            <div class="mt-auto pt-3"></div>
                            @foreach($competition->vote_categories as $voteCategory)
                                <div class="points my-2"
                                     x-data="partymeisterRating({
                                         entryId: {{ $entry->id }},
                                         voteCategoryId: {{ $voteCategory->id }},
                                         negative: {{ $voteCategory->has_negative }},
                                         stars: {{ $voteCategory->points }},
                                         readonly: {{ $votingDeadlineOver ? 'true' : 'false' }},
                                         value: {{ isset($votes[$voteCategory->id][$entry->id]) ? $votes[$voteCategory->id][$entry->id]['points'] : 0 }}
                                     })"
                                     data-entry-id="{{$entry->id}}"
                                     data-vote-category-id="{{$voteCategory->id}}">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <template x-if="negative">
                                            <template x-for="n in stars" :key="'neg-'+n">
                                                <button type="button" class="w-11 h-11 text-2xl cursor-pointer flex items-center justify-center"
                                                        x-bind:class="currentValue <= -n ? 'text-error opacity-100' : 'text-error opacity-30'"
                                                        x-on:click="!readonly && rate(-n)"
                                                        x-text="'\u2716'"></button>
                                            </template>
                                        </template>
                                        <button type="button" class="w-11 h-11 text-2xl cursor-pointer flex items-center justify-center"
                                                x-bind:class="currentValue === 0 ? 'text-accent opacity-100' : 'text-accent opacity-30'"
                                                x-on:click="!readonly && rate(0)"
                                                x-text="'\u2205'"></button>
                                        <template x-for="n in stars" :key="'pos-'+n">
                                            <button type="button" class="w-11 h-11 text-2xl cursor-pointer flex items-center justify-center"
                                                    x-bind:class="currentValue >= n ? 'text-accent opacity-100' : 'text-accent opacity-30'"
                                                    x-on:click="!readonly && rate(n)"
                                                    x-text="'\u2605'"></button>
                                        </template>
                                    </div>
                                </div>
                                @if ($loop->last && $voteCategory->has_comment && !$votingDeadlineOver)
                                    <div class="flex w-full">
                                        <input class="flex-1 rounded-s-lg border border-border bg-body px-4 py-2 text-heading placeholder-text-muted focus:border-accent focus:outline-none focus:ring-1 focus:ring-accent transition-colors" placeholder="Comment" type="text" name="entry_comment[{{$competition->id}}][{{$entry->id}}]"
                                               value="{{ (isset($votes[$voteCategory->id][$entry->id]) ? $votes[$voteCategory->id][$entry->id]['comment'] : '')}}">
                                        <button type="button" class="rounded-e-lg bg-accent px-4 py-2 font-medium text-body hover:bg-accent-hover transition-colors"
                                                x-on:click="saveComment({{ $entry->id }}, {{ $voteCategory->id }}, $el.parentElement.querySelector('input').value)">Send</button>
                                    </div>
                                @endif
                                @if ($loop->last && $voteCategory->has_special_vote && !$votingDeadlineOver)
                                    <div class="mt-2">
                                        <button type="button" class="w-full inline-flex items-center justify-center rounded-lg bg-success px-3 py-1.5 text-sm font-medium text-body hover:bg-success/90 transition-colors"
                                                x-show="specialVoteEntryId !== {{ $entry->id }}"
                                                x-on:click="setSpecialVote({{ $entry->id }}, {{ $voteCategory->id }}, true)">
                                            &hearts; My party favourite &hearts;
                                        </button>
                                        <button type="button" class="w-full inline-flex items-center justify-center rounded-lg bg-accent px-3 py-1.5 text-sm font-medium text-body hover:bg-accent-hover transition-colors"
                                                x-show="specialVoteEntryId === {{ $entry->id }}"
                                                x-on:click="setSpecialVote({{ $entry->id }}, {{ $voteCategory->id }}, false)">
                                            &#x2639; Not my favourite anymore &#x2639;
                                        </button>
                                    </div>
                                @endif
                            @endforeach
                            @if ($entry->download != null)
                                <a href="{{$entry->download->getUrl()}}" class="w-full inline-flex items-center justify-center rounded-lg bg-accent px-3 py-1.5 text-sm font-medium text-body hover:bg-accent-hover transition-colors mt-4 no-underline">
                                    Download
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </form>
@endif
@if (!is_null($competition))
@section('view-scripts')
    <script type="module">
        document.addEventListener('alpine:init', () => {
            Alpine.data('partymeisterRating', (config) => ({
                entryId: config.entryId,
                voteCategoryId: config.voteCategoryId,
                negative: config.negative,
                stars: config.stars,
                readonly: config.readonly,
                currentValue: config.value,
                rate(points) {
                    this.currentValue = points;
                    this.submitVote(points);
                },
                submitVote(points) {
                    const formEl = this.$el.closest('form');
                    const formData = Alpine.$data(formEl);
                    if (formData && formData.vote) {
                        formData.vote(this.entryId, this.voteCategoryId, points);
                    }
                }
            }));

            Alpine.data('votingForm', (competitionId, voteUrl, deadlineOver) => ({
                competitionId: competitionId,
                voteUrl: voteUrl,
                deadlineOver: deadlineOver,
                specialVoteEntryId: @php
                    $svEntryId = null;
                    foreach ($votes as $catVotes) {
                        foreach ($catVotes as $entryId => $voteData) {
                            if (!empty($voteData['special_vote'])) {
                                $svEntryId = $entryId;
                                break 2;
                            }
                        }
                    }
                @endphp {!! $svEntryId !== null ? (int) $svEntryId : 'null' !!},
                vote(entryId, voteCategoryId, points, specialVote) {
                    if (this.deadlineOver) return;
                    let data = {
                        entry_id: entryId,
                        competition_id: this.competitionId,
                        vote_category_id: voteCategoryId,
                        points: points,
                        comment: '',
                    };

                    const commentInput = document.querySelector(`input[name="entry_comment[${this.competitionId}][${entryId}]"]`);
                    if (commentInput) {
                        data.comment = commentInput.value;
                    }

                    if (specialVote !== undefined) {
                        data.special_vote = specialVote;
                    }

                    axios.post(this.voteUrl + '/' + entryId, data).then((response) => {
                        if (response.data.success) {
                            toastr.success(response.data.message);
                        } else if (response.data.error) {
                            toastr.error(response.data.message);
                        }
                    });
                },
                saveComment(entryId, voteCategoryId, comment) {
                    const ratingEl = document.querySelector(`.points[data-entry-id="${entryId}"][data-vote-category-id="${voteCategoryId}"]`);
                    const ratingData = ratingEl ? Alpine.$data(ratingEl) : null;
                    const points = ratingData ? ratingData.currentValue : 0;

                    let data = {
                        entry_id: entryId,
                        competition_id: this.competitionId,
                        vote_category_id: voteCategoryId,
                        points: points,
                        comment: comment,
                    };

                    axios.post(this.voteUrl + '/' + entryId, data).then((response) => {
                        if (response.data.success) {
                            toastr.success(response.data.message);
                        } else if (response.data.error) {
                            toastr.error(response.data.message);
                        }
                    });
                },
                setSpecialVote(entryId, voteCategoryId, value) {
                    if (this.deadlineOver) return;
                    this.specialVoteEntryId = value ? entryId : null;

                    const ratingEl = document.querySelector(`.points[data-entry-id="${entryId}"][data-vote-category-id="${voteCategoryId}"]`);
                    const ratingData = ratingEl ? Alpine.$data(ratingEl) : null;
                    const points = ratingData ? ratingData.currentValue : 0;

                    this.vote(entryId, voteCategoryId, points, value);
                }
            }));
        });
    </script>
@append
@endif
