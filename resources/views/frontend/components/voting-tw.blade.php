<h4 class="text-lg font-bold mb-4">
    Voting
</h4>
@if ($votingDeadlineOver)
    <div class="rounded-lg border border-accent/30 bg-accent/10 px-4 py-3 text-sm text-accent mb-4">
        <span>Voting deadline is over!</span>
    </div>
@endif
@if ($liveVoting)
    <div class="rounded-lg border border-success/30 bg-success/10 px-4 py-3 text-sm text-success mb-4">
        <a class="text-pink-500 font-bold" href="{{ route('frontend.pages.index', ['slug' => $component->live_voting_page->full_slug])}}">
            Live voting for the {{$liveVotingCompetition}} is active now!
            <strong>Go vote!</strong></a>
    </div>
@endif
@if (is_null($competition) && $liveVoting == false)
    <div class="rounded-lg border border-accent/30 bg-accent/10 px-4 py-3 text-sm text-accent mb-4">
        <span>There are no entries to vote for yet!</span>
    </div>
@endif
@if (!is_null($competition))
    <form id="save-votes" action="{{ route('frontend.pages.index', ['slug' => 'voting'])}}?competition_id={{$competition->id}}"
          method="post"
          x-data="votingForm({{ $competition->id }}, '{{ route('ajax.votes.submit', ['api_token' => $visitor->api_token]) }}', {{ $votingDeadlineOver ? 'true' : 'false' }})">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <h4 class="text-lg font-bold mb-4">{{$competition->name}}</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 entries">
            @foreach ($competition->entries()->where('status', 1)->orderBy('sort_position', 'ASC')->get() as $entry)
                <div>
                    <div class="rounded-lg bg-surface shadow-[0_2px_8px_rgba(0,0,0,0.3)]"
                         x-bind:class="{ 'ring-2 ring-accent': specialVoteEntryId === {{ $entry->id }} }"
                         data-entry-id="{{$entry->id}}">
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
                            <h5 class="text-heading font-semibold text-base mb-3">{{$entry->title}} @if (!$entry->competition->competition_type->is_anonymous)by {{$entry->author}}@endif</h5>
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
                            @if ($entry->description != '')
                                <h6 class="text-sm font-semibold">Description</h6>
                                <p class="mt-1 text-sm">{!! nl2br($entry->description)!!}</p>
                            @endif
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
                                    <div class="flex items-center justify-center gap-1">
                                        <template x-if="negative">
                                            <template x-for="n in stars" :key="'neg-'+n">
                                                <button type="button" class="w-6 h-6 cursor-pointer"
                                                        x-bind:class="currentValue <= -n ? 'text-error opacity-100' : 'text-error opacity-30'"
                                                        x-on:click="!readonly && rate(-n)"
                                                        x-text="'\u2716'"></button>
                                            </template>
                                        </template>
                                        <button type="button" class="w-6 h-6 cursor-pointer"
                                                x-bind:class="currentValue === 0 ? 'text-warning opacity-100' : 'text-warning opacity-30'"
                                                x-on:click="!readonly && rate(0)"
                                                x-text="'\u2205'"></button>
                                        <template x-for="n in stars" :key="'pos-'+n">
                                            <button type="button" class="w-6 h-6 cursor-pointer"
                                                    x-bind:class="currentValue >= n ? 'text-warning opacity-100' : 'text-warning opacity-30'"
                                                    x-on:click="!readonly && rate(n)"
                                                    x-text="'\u2605'"></button>
                                        </template>
                                    </div>
                                </div>
                                @if ($loop->last && $voteCategory->has_comment)
                                    <div class="flex w-full">
                                        <input @if ($votingDeadlineOver)disabled @endif class="flex-1 rounded-l-lg border border-border bg-body px-4 py-2 text-heading placeholder-text-muted focus:border-accent focus:outline-none focus:ring-1 focus:ring-accent transition-colors" placeholder="Comment" type="text" name="entry_comment[{{$competition->id}}][{{$entry->id}}]"
                                               value="{{ (isset($votes[$voteCategory->id][$entry->id]) ? $votes[$voteCategory->id][$entry->id]['comment'] : '')}}">
                                        <button type="button" class="rounded-r-lg bg-success px-4 py-2 text-sm font-semibold text-body hover:bg-success/90 transition-colors"
                                                x-on:click="saveComment({{ $entry->id }}, {{ $voteCategory->id }}, $el.parentElement.querySelector('input').value)">Send</button>
                                    </div>
                                @endif
                                @if ($loop->last && $voteCategory->has_special_vote)
                                    <div class="mt-2">
                                        <button type="button" class="w-full inline-flex items-center justify-center rounded-lg bg-success px-3 py-1.5 text-xs font-semibold text-body hover:bg-success/90 transition-colors"
                                                x-show="specialVoteEntryId !== {{ $entry->id }}"
                                                x-on:click="setSpecialVote({{ $entry->id }}, {{ $voteCategory->id }}, true)">
                                            &hearts; My party favourite &hearts;
                                        </button>
                                        <button type="button" class="w-full inline-flex items-center justify-center rounded-lg bg-accent px-3 py-1.5 text-xs font-semibold text-body hover:bg-accent-hover transition-colors"
                                                x-show="specialVoteEntryId === {{ $entry->id }}"
                                                x-on:click="setSpecialVote({{ $entry->id }}, {{ $voteCategory->id }}, false)">
                                            &#x2639; Not my favourite anymore &#x2639;
                                        </button>
                                    </div>
                                @endif
                            @endforeach
                            @if ($entry->download != null)
                                <a href="{{$entry->download->getUrl()}}" class="w-full inline-flex items-center justify-center rounded-lg bg-success px-3 py-1.5 text-xs font-semibold text-body hover:bg-success/90 transition-colors mt-4 no-underline">
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
                specialVoteEntryId: {!! json_encode(
                    collect($votes)->flatMap(function($entries) {
                        return collect($entries)->filter(fn($v) => isset($v['special_vote']) && $v['special_vote'] == 1);
                    })->keys()->first()
                ) !!},
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

                    axios.post(this.voteUrl, data).then((response) => {
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

                    axios.post(this.voteUrl, data).then((response) => {
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
