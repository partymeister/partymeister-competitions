<div id="live-voting">
    <h2>Live voting</h2>
    <h3>@{{ competition }}</h3>

    <div class="card" v-for="entry in entries">
        <div class="section" v-bind:class="{ special: (entry.vote && entry.vote.special_vote && entry.vote_category_has_special_vote)}">
            <h4><strong># @{{ entry.sort_position_prefixed }}</strong> @{{ entry.title }} by @{{ entry.author }}</h4>
            <div style="text-align: center;">
                <div data-value="0" @click="updateVote(entry, 0)"
                     v-bind:class="{ 'partymeister-rating-wrapper': true, 'partymeister-rating-cancel-on': (entry.vote && entry.vote.points) == 0, 'partymeister-rating-cancel-off': (entry.vote && entry.vote.points) != 0}"></div>
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
            <span class="toast secondary" v-if="error" style="background-color: #d9534f; color: #fff;">Error</span>
        </div>
    </div>
</div>

@section('view-scripts')
    <script type="module">
        const { createApp, ref, onMounted } = Vue;

        const liveVotingApp = createApp({
            setup() {
                const competition = ref('');
                const entries = ref([]);
                const success = ref(false);
                const error = ref(false);

                function refresh() {
                    axios.get('{{url('/api/profile/'.$visitor->api_token.'/votes/live')}}').then((response) => {
                        if (response.status == 204) {
                            return;
                        }
                        let newEntries = response.data.data;
                        if (newEntries.length > 0) {
                            competition.value = newEntries[0].competition_name;
                        }
                        for (let [index, ne] of newEntries.entries()) {
                            if (newEntries[index].vote.length == 0) {
                                newEntries[index].vote.push({
                                    comment: '',
                                    special_vote: false,
                                    points: 0
                                });
                            }
                            newEntries[index].comment = newEntries[index].vote.comment;
                            for (let e of entries.value) {
                                if (e.id == ne.id) {
                                    if (newEntries[index].vote.length == 0 || newEntries[index].vote.comment != e.comment) {
                                        newEntries[index].comment = e.comment;
                                    }
                                }
                            }
                        }
                        entries.value = newEntries;
                    });
                }

                function markSpecial(entry, value) {
                    for (let e of entries.value) {
                        if (e.vote.special_vote == true) {
                            e.vote.special_vote = false;
                        }
                    }
                    entry.vote.special_vote = value;
                    saveVote(entry, value);
                }

                function updateVote(entry, points) {
                    if (points != undefined) {
                        entry.vote.points = points;
                    }
                    saveVote(entry);
                }

                function saveVote(entry, special) {
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
                            success.value = true;
                            setTimeout(() => { success.value = false; }, 2000);
                        } else if (response.data.error) {
                            error.value = true;
                            setTimeout(() => { error.value = false; }, 2000);
                        }
                    });
                }

                onMounted(() => {
                    const configRefreshInterval = '{{config('partymeister-competitions-voting.live-refresh-interval')}}';
                    const refreshInterval = Number(configRefreshInterval || 20000);
                    refresh();
                    window.setInterval(refresh, refreshInterval);
                });

                return { competition, entries, success, error, updateVote, markSpecial };
            }
        });

        liveVotingApp.mount('#live-voting');
    </script>
@endsection
