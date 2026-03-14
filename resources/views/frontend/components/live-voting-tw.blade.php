<div x-data="liveVoting"
     data-refresh-url="{{ url('/api/profile/'.$visitor->api_token.'/votes/live') }}"
     data-vote-url="{{ route('ajax.votes.submit', ['api_token' => $visitor->api_token]) }}"
     data-refresh-interval="{{ config('partymeister-competitions-voting.live-refresh-interval', 20000) }}">
    <h3 class="mb-4">Live voting</h3>
    <h4 class="mb-4" x-text="competition"></h4>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-stretch">
        <template x-for="entry in entries" :key="entry.id">
            <div class="flex">
                <div class="flex-1 flex flex-col rounded-lg bg-surface border border-border shadow-[0_4px_12px_rgba(0,0,0,0.4)]"
                     :class="{ 'ring-2 ring-accent': entry.vote && entry.vote.special_vote && entry.vote_category_has_special_vote }">
                    <div class="p-5 flex-1 flex flex-col">
                        <h5 class="mb-1"><strong>#<span x-text="entry.sort_position_prefixed"></span></strong> <span x-text="entry.title"></span></h5>
                        <h6 class="text-text-muted">by <span x-text="entry.author"></span></h6>

                        <div class="mt-auto pt-3"></div>

                        {{-- Star rating --}}
                        <div class="flex items-center justify-center gap-1.5 my-2">
                            <button type="button" class="w-11 h-11 text-2xl cursor-pointer flex items-center justify-center"
                                    :class="(entry.vote && entry.vote.points) == 0 ? 'text-accent opacity-100' : 'text-accent opacity-30'"
                                    @click="updateVote(entry, 0)">&oslash;</button>
                            <template x-for="points in entry.vote_category_points" :key="points">
                                <button type="button" class="w-11 h-11 text-2xl cursor-pointer flex items-center justify-center"
                                        :class="(entry.vote && entry.vote.points) >= points ? 'text-accent opacity-100' : 'text-accent opacity-30'"
                                        @click="updateVote(entry, points)">&starf;</button>
                            </template>
                        </div>

                        {{-- Comment --}}
                        <template x-if="entry.vote_category_has_comment">
                            <div class="flex w-full">
                                <input placeholder="Comment" x-model="entry.comment"
                                       class="flex-1 rounded-s-lg border border-border bg-body px-4 py-2 text-heading placeholder-text-muted focus:border-accent focus:outline-none focus:ring-1 focus:ring-accent transition-colors">
                                <button class="rounded-e-lg bg-accent px-4 py-2 font-medium text-body hover:bg-accent-hover transition-colors"
                                        @click="updateVote(entry)">Send</button>
                            </div>
                        </template>

                        {{-- Special vote --}}
                        <template x-if="entry.vote_category_has_special_vote">
                            <div class="mt-2">
                                <button x-show="!(entry.vote && entry.vote.special_vote)"
                                        class="w-full inline-flex items-center justify-center rounded-lg bg-success px-3 py-1.5 text-sm font-medium text-body hover:bg-success/90 transition-colors"
                                        @click="markSpecial(entry, true)">&hearts; My party favourite &hearts;</button>
                                <button x-show="entry.vote && entry.vote.special_vote"
                                        class="w-full inline-flex items-center justify-center rounded-lg bg-accent px-3 py-1.5 text-sm font-medium text-body hover:bg-accent-hover transition-colors"
                                        @click="markSpecial(entry, false)">&#x2639; Not my favourite anymore &#x2639;</button>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </template>
    </div>
</div>
