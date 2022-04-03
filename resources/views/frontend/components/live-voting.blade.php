<div id="live-voting">
    <h2>Live voting</h2>
    <h3>@{{ competition }}</h3>

    <div class="card" v-for="entry in entries">
        <div class="section" v-bind:class="{ special: (entry.vote && entry.vote.special_vote && entry.vote_category_has_special_vote)}">
            <h4><strong># @{{ entry.sort_position_prefixed }}</strong> @{{ entry.title }} by @{{ entry.author }}</h4>
            <div style="text-align: center;">
                <div data-value="0" @click="updateVote(entry, 0)"
                     v-bind:class="{ 'partymeister-rating-wrapper': true, 'partymeister-rating-cancel-on': (entry.vote && entry.vote.points) == 0, 'partymeister-rating-cancel-off': (entry.vote && entry.vote.points) != 0}"></div>
{{--                <div data-value="-1" @click="updateVote(entry, -1)"--}}
{{--                     v-bind:class="{ 'partymeister-rating-wrapper': true, 'partymeister-rating-negative-on': (entry.vote.data[0] && entry.vote.data[0].points) == -1, 'partymeister-rating-negative-off': (entry.vote.data[0] && entry.vote.data[0].points) != -1}"></div>--}}
                <template v-for="points in entry.vote_category_points">
                    <div v-bind:data-value="points" @click="updateVote(entry, points)"
                         v-bind:class="{ 'partymeister-rating-wrapper': true, 'partymeister-rating-star-on': (entry.vote && entry.vote.points) >= points, 'partymeister-rating-star-off': (entry.vote && entry.vote.points) < points}"></div>
                </template>
            </div>
            <div style="text-align: center">
                <template v-if="entry.vote_category_has_comment">
                    <input name="comment" placeholder="Comment" v-model="entry.comment" style="text-align: center">
                    <button @click="updateVote(entry)">Send</button>
                </template>

                <template v-if="entry.vote_category_has_special_vote">
                    <button v-if="!(entry.vote && entry.vote.special_vote)"
                            @click="markSpecial(entry, true)">&hearts; My party
                        favourite &hearts;
                    </button>
                    <button v-if="entry.vote && entry.vote.special_vote" @click="markSpecial(entry, false)">
                        &#x2639; Not my favourite
                        anymore &#x2639;
                    </button>
                </template>
            </div>
            <span class="toast secondary" v-if="success">Saved</span>
            <span class="toast secondary" v-if="error">Error</span>
        </div>
    </div>
</div>

@section('view-scripts')
    <script>
        let liveVoting = new Vue({
            el: '#live-voting',
            data: {
                competition: '',
                entries: [],
                success: false,
                error: false,
            },
            methods: {
                refresh() {
                    axios.get('{{url('/api/profile/'.$visitor->api_token.'/votes/live')}}').then((response) => {
                        if (response.status == 204) {
                            return;
                        }
                        let entries = response.data.data;
                        if (entries.length > 0) {
                            this.competition = entries[0].competition_name;
                        }
                        // Save state
                        for (let [index, ne] of entries.entries()) {
                            if (entries[index].vote.length == 0) {
                                entries[index].vote.push({
                                    comment: '',
                                    special_vote: false,
                                    points: 0
                                });
                            }
                            entries[index].comment = entries[index].vote.comment;
                            // Save state
                            for (let e of this.entries) {
                                if (e.id == ne.id) {
                                    if (entries[index].vote.length == 0 || entries[index].vote.comment != e.comment) {
                                        entries[index].comment = e.comment;
                                    }
                                }
                            }
                        }
                        this.entries = entries;
                    });
                },
                markSpecial(entry, value) {
                    for (let e of this.entries) {
                        if (e.vote.special_vote == true) {
                            e.vote.special_vote = false;
                        }
                        // if (e.vote.data.special_vote) {
                        //     Vue.set(e.vote.data, 'special_vote', !value);
                        // }
                    }
                    entry.vote.special_vote = value;
                    this.saveVote(entry, value);
                },
                updateVote(entry, points) {
                    if (points != undefined) {
                        entry.vote.points = points;
                    }
                    this.saveVote(entry);
                },
                saveVote(entry, special) {
                    let data = {
                        entry_id: entry.id,
                        competition_id: entry.competition_id,
                        vote_category_id: entry.vote_category_id,
                        points: entry.vote.points,
                        comment: entry.comment === null ? '' : entry.comment,
                        live: true
                    };


                    if (special != undefined) {
                        data.special_vote = special;
                    }

                    axios.post('{{route('ajax.votes.submit', ['api_token' => $visitor->api_token])}}', data).then((response) => {
                        if (response.data.success) {
                            this.success = true;
                            window.setTimeout(function () {
                                this.success = false
                            }, 2000);
                        } else if (response.data.error) {
                            this.error = true;
                            window.setTimeout(function () {
                                this.error = false
                            }, 2000);
                        }
                    });
                }
            },
            mounted: function () {

                this.refresh();

                window.setInterval(this.refresh, 20000);

            }
        });
    </script>
@endsection
