@if (count($competitions) > 0)
    <h4>Please choose a competition</h4>
    <ul class="vertical menu">
        @foreach ($competitions as $c)
            <li @if(isset($activeCompetitionId) && $activeCompetitionId == $c->id)class="is-active"@endif>
                <a href="{{Request::url()}}?competition_id={{$c->id}}">
                    {{$c->name}}
                </a>
            </li>
        @endforeach
    </ul>
@endif
