<div id="live-voting">
    <h2 class="text-2xl font-bold mb-2">Live voting</h2>
    <h3 class="text-xl font-semibold mb-4">@{{ competition }}</h3>

    <div class="rounded-lg bg-surface shadow-[0_2px_8px_rgba(0,0,0,0.3)] mb-4" v-for="entry in entries" :key="entry.id">
        <div class="p-5" v-bind:class="{ 'ring-2 ring-accent': (entry.vote && entry.vote.special_vote && entry.vote_category_has_special_vote)}">
            <h4 class="text-heading font-semibold text-base mb-3"><strong># @{{ entry.sort_position_prefixed }}</strong> @{{ entry.title }} by @{{ entry.author }}</h4>
            <div class="text-center my-2">
                <div class="inline-block cursor-pointer w-6 h-6"
                     data-value="0" @click="updateVote(entry, 0)"
                     v-bind:class="{ 'partymeister-rating-wrapper': true, 'partymeister-rating-cancel-on': (entry.vote && entry.vote.points) == 0, 'partymeister-rating-cancel-off': (entry.vote && entry.vote.points) != 0}"></div>
                <template v-for="points in entry.vote_category_points" :key="points">
                    <div class="inline-block cursor-pointer w-6 h-6"
                         v-bind:data-value="points" @click="updateVote(entry, points)"
                         v-bind:class="{ 'partymeister-rating-wrapper': true, 'partymeister-rating-star-on': (entry.vote && entry.vote.points) >= points, 'partymeister-rating-star-off': (entry.vote && entry.vote.points) < points}"></div>
                </template>
            </div>
            <div class="text-center">
                <template v-if="entry.vote_category_has_comment">
                    <div class="flex w-full max-w-md mx-auto mb-2">
                        <input name="comment" placeholder="Comment" v-model="entry.comment" class="flex-1 rounded-l-lg border border-border bg-body px-4 py-2 text-heading text-center placeholder-text-muted focus:border-accent focus:outline-none focus:ring-1 focus:ring-accent transition-colors">
                        <button class="rounded-r-lg bg-accent px-4 py-2 text-sm font-semibold text-body hover:bg-accent-hover transition-colors" @click="updateVote(entry)">Send</button>
                    </div>
                </template>

                <template v-if="entry.vote_category_has_special_vote">
                    <button v-if="!(entry.vote && entry.vote.special_vote)"
                            class="w-full inline-flex items-center justify-center rounded-lg bg-success px-3 py-1.5 text-xs font-semibold text-body hover:bg-success/90 transition-colors"
                            @click="markSpecial(entry, true)">&hearts; My party
                        favourite &hearts;
                    </button>
                    <button v-if="entry.vote && entry.vote.special_vote"
                            class="w-full inline-flex items-center justify-center rounded-lg bg-accent px-3 py-1.5 text-xs font-semibold text-body hover:bg-accent-hover transition-colors"
                            @click="markSpecial(entry, false)">
                        &#x2639; Not my favourite
                        anymore &#x2639;
                    </button>
                </template>
            </div>
            <div class="fixed bottom-4 right-4 z-50 space-y-2" v-if="success">
                <div class="rounded-lg border border-success/30 bg-success/10 px-4 py-3 text-sm text-success"><span>Saved</span></div>
            </div>
            <div class="fixed bottom-4 right-4 z-50 space-y-2" v-if="error">
                <div class="rounded-lg border border-error/30 bg-error/10 px-4 py-3 text-sm text-error"><span>Error</span></div>
            </div>
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
