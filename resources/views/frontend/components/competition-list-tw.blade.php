@if (count($competitions) > 0)
    <h4 class="text-lg font-bold mb-4">Please choose a competition</h4>
    <ul class="rounded-lg bg-surface p-2 space-y-0.5 w-full">
        @foreach ($competitions as $c)
            <li>
                <a href="{{Request::url()}}?competition_id={{$c->id}}"
                   class="block px-3 py-2 rounded-md transition-colors hover:bg-surface-raised hover:text-heading @if(isset($activeCompetitionId) && $activeCompetitionId == $c->id) bg-surface-raised text-heading font-medium @endif">
                    {{$c->name}}
                </a>
            </li>
        @endforeach
    </ul>
@endif
