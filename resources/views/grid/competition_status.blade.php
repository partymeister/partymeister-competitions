@if (!$record->competition_type->has_out_of_competition_voting)
    <button type="button" data-toggle="tooltip" data-placement="top" data-record="{{$record->id}}" data-class="btn-success" data-class-alternate="btn-outline-secondary" data-upload-enabled="{{(int)!$record->upload_enabled}}" @if ($record->voting_enabled) disabled @endif class="change-competition-upload btn @defaultButtonSize @if ($record->upload_enabled == 1)btn-success @else btn-outline-secondary @endif" title="{{trans('partymeister-competitions::backend/competitions.upload_enabled')}}">UPL</button>
    <button type="button" data-toggle="tooltip" data-placement="top" data-record="{{$record->id}}" data-class="btn-success" data-class-alternate="btn-outline-secondary" data-voting-enabled="{{(int)!$record->voting_enabled}}" @if ($record->upload_enabled) disabled @endif class="change-competition-voting btn @defaultButtonSize @if ($record->voting_enabled == 1)btn-success @else btn-outline-secondary @endif" title="{{trans('partymeister-competitions::backend/competitions.voting_enabled')}}">VOTE</button>
@else
    <button type="button" data-toggle="tooltip" data-placement="top" data-record="{{$record->id}}" data-class="btn-success" data-class-alternate="btn-outline-secondary" data-live-voting-enabled="{{(int)!$record->live_voting_enabled}}" class="change-competition-live-voting btn @defaultButtonSize @if ($record->live_voting_enabled == 1)btn-success @else btn-outline-secondary @endif" title="{{trans('partymeister-competitions::backend/competitions.live_voting_button')}}">LIVE VOTING</button>
@endif

<div class="dropdown show float-right">
    <a class="btn btn-outline-secondary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        {{trans('partymeister-competitions::backend/competitions.actions')}}
    </a>

    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
        <a class="dropdown-item" href="{{route('backend.competitions.playlist.index', ['competition' => $record->id])}}?format=slides">{{trans('partymeister-competitions::backend/competitions.generate_playlist')}}</a>
        <a class="dropdown-item" href="{{route('backend.competitions.playlist.index', ['competition' => $record->id])}}?format=json&download=true">{{trans('partymeister-competitions::backend/competitions.download_json_playlist')}}</a>
        <a class="dropdown-item" href="{{route('backend.competitions.playlist.index', ['competition' => $record->id])}}?format=m3u&download=true">{{trans('partymeister-competitions::backend/competitions.download_m3u_playlist')}}</a>
        <a class="dropdown-item" href="{{route('backend.competitions.playlist.index', ['competition' => $record->id])}}?format=timecode&download=true">{{trans('partymeister-competitions::backend/competitions.download_callback_timecodes')}}</a>
        <a class="dropdown-item btn disabled" href="#">{{trans('partymeister-competitions::backend/competitions.show_live_voting')}}</a>
    </div>
</div>
