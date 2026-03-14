@if (count($competitions) > 0)
    <h4 class="text-lg font-bold mb-4">Please choose a competition</h4>
    <ul class="menu menu-vertical bg-base-200 rounded-box w-full">
        @foreach ($competitions as $c)
            <li>
                <a href="{{Request::url()}}?competition_id={{$c->id}}"
                   @if(isset($activeCompetitionId) && $activeCompetitionId == $c->id) class="menu-active" @endif>
                    {{$c->name}}
                </a>
            </li>
        @endforeach
    </ul>
@endif
