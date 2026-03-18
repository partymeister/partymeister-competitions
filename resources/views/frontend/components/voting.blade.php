<h4>
    Voting
</h4>
@if ($votingDeadlineOver)
    <div class="callout warning">
        Voting deadline is over!
    </div>
@endif
@if ($liveVoting)
    <div class="callout success">
        <a style="color: #fc4981" href="{{ route('frontend.pages.index', ['slug' => $component->live_voting_page->full_slug])}}">
            Live voting for the {{$liveVotingCompetition}} is active now!
            <strong>Go vote!</strong></a>
    </div>
@endif
@if (is_null($competition) && $liveVoting == false)
    <div class="callout warning">
        There are no entries to vote for yet!
    </div>
@endif
@if (!is_null($competition))
    <form id="save-votes" action="{{ route('frontend.pages.index', ['slug' => 'voting'])}}?competition_id={{$competition->id}}"
          method="post">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <h4>{{$competition->name}}</h4>
        <div class="grid-x grid-margin-x grid-margin-y entries" data-equalizer data-equalize-by-row="true">
            @foreach ($competition->entries()->where('status', 1)->orderBy('sort_position', 'ASC')->get() as $entry)
                <div class="cell medium-6 small-12">

                    <div class="card @if(isset($votes[1][$entry->id]) && $votes[1][$entry->id]['special_vote'] == 1) special-vote-highlight @endif" data-entry-id="{{$entry->id}}" data-equalizer-watch>
                        @if($entry->getFirstMedia('screenshot'))
                            <div class="image-wrapper">
                                <a data-caption="{{$entry->title}} @if (!$entry->competition->competition_type->is_anonymous)by {{$entry->author}}@endif" data-fancybox="gallery"
                                   href="{{$entry->getFirstMedia('screenshot')->getUrl('preview')}}">
                                    <img src="{{$entry->getFirstMedia('screenshot')->getUrl('preview')}}"
                                         class="img-fluid">
                                </a>
                            </div>
                        @endif
                        @if($entry->getFirstMedia('audio'))
                            <audio controls src="{{$entry->getFirstMedia('audio')->getUrl()}}" style="width: 100%"></audio>
                        @endif
                        <div class="card-section">
                            <h5>{{$entry->title}} @if (!$entry->competition->competition_type->is_anonymous)by {{$entry->author}}@endif</h5>
                            <h6>{{$entry->competition->name}}</h6>
                            @if ($entry->options->count() > 0 || $entry->custom_option != '')
                                <h6 class="mt-2">Options</h6>
                                <ul class="list-unsorted">
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
                                <p class="mt-2">{!! nl2br(e($entry->description)) !!}</p>
                            @endif
                            @foreach($competition->vote_categories as $voteCategory)
                                <div class="points" data-entry-id="{{$entry->id}}"
                                     data-vote-category-id="{{$voteCategory->id}}" data-negative="{{$voteCategory->has_negative}}" data-value="{{ (isset($votes[$voteCategory->id][$entry->id]) ? $votes[$voteCategory->id][$entry->id]['points'] : 0)}}" data-points="{{$voteCategory->points}}"></div>
                                @if ($loop->last && $voteCategory->has_comment)
                                    <div class="input-group">
                                        <input @if ($votingDeadlineOver)disabled @endif class="input-group-field entry-comment" placeholder="Comment" type="text" name="entry_comment[{{$competition->id}}][{{$entry->id}}]"
                                               value="{{ (isset($votes[$voteCategory->id][$entry->id]) ? $votes[$voteCategory->id][$entry->id]['comment'] : '')}}">
                                        <div class="input-group-button">
                                            <button class="button success save-comment">Send</button>
                                        </div>
                                    </div>
                                @endif
                                @if ($loop->last && $voteCategory->has_special_vote)
                                    <div>
                                        <button class="button success small expanded special-vote-on @if(isset($votes[$voteCategory->id][$entry->id]) && $votes[$voteCategory->id][$entry->id]['special_vote'] == 1) hide @endif">
                                            &hearts; My party
                                            favourite &hearts;
                                        </button>
                                        <button class="button warning small expanded special-vote-off @if (!isset($votes[$voteCategory->id][$entry->id]) || $votes[$voteCategory->id][$entry->id]['special_vote'] == 0) hide @endif">
                                            &#x2639; Not my
                                            favourite anymore &#x2639;
                                        </button>
                                    </div>
                                @endif
                            @endforeach
                            @if ($entry->download != null)
                                <div class="clearfix"></div>
                                <a href="{{$entry->download->getUrl()}}" style="text-decoration: none !important">
                                    <button type="button" class="button small success expanded" style="margin-bottom: 50px;">
                                        Download
                                    </button>
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
        $(document).ready(function () {

            $('.special-vote-on').on('click', function (e) {
                e.preventDefault();

                let ratingElement = $(this).parent().parent().find('.points');
                vote($(ratingElement).data('value'), ratingElement, true);

                $('.special-vote-off').each(function (index, element) {
                    if (!$(element).hasClass('hide')) {
                        $(element).addClass('hide');
                        $(element).parent().find('.special-vote-on').removeClass('hide');
                    }
                });
                $('.special-vote-off').addClass('hide');

                $('.entries div .card').removeClass('special-vote-highlight');
                $(this).parent().parent().parent().addClass('special-vote-highlight');
                $(this).addClass('hide');
                $(this).parent().find('.special-vote-off').removeClass('hide');
            });

            $('.special-vote-off').on('click', function (e) {
                e.preventDefault();

                let ratingElement = $(this).parent().parent().find('.points');
                vote($(ratingElement).data('value'), ratingElement, false);

                $('.entries div .card').removeClass('special-vote-highlight');
                $(this).addClass('hide');
                $(this).parent().find('.special-vote-on').removeClass('hide');
            });

            let vote = function (rating, element, specialVote) {
                let data = {
                    entry_id: $(element).data('entry-id'),
                    competition_id: {{$competition->id}},
                    vote_category_id: $(element).data('vote-category-id'),
                    points: rating,
                    comment: $(element).parent().find('input[type="text"]').val(),
                };


                if (specialVote != undefined) {
                    data.special_vote = specialVote;
                }

                axios.post('{{route('ajax.votes.submit', ['api_token' => $visitor->api_token])}}', data).then(function (response) {
                    if (response.data.success) {
                        toastr.success(response.data.message);
                    } else if (response.data.error) {
                        toastr.error(response.data.message);
                    }
                });
            };

            $('.save-comment').on('click', function(e) {
                e.preventDefault();
                let ratingElement = $(this).parents().find('.points');
                vote($(ratingElement).data('value'), ratingElement);
            });

            $('.points').each(function (index, element) {
                points = $(element).parent().find('input:hidden[data-vote-category-id="' + $(element).data('vote-category-id') + '"]').val();
                $(element).partymeisterRating({
                    negative: parseInt($(element).data('negative')),
                    stars: $(element).data('points'),
                    @if ($votingDeadlineOver)
                    readonly: true,
                    @endif
                    value: points,
                    click: function (points, element) {
                        vote(points, element);
                    }
                });
            });
        });
    </script>
@append
@endif
