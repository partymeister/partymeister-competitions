<button type="button" data-toggle="tooltip" data-placement="top" data-record="{{$record->id}}" data-class="btn-success" data-class-alternate="btn-outline-secondary" data-upload-enabled="{{(int)!$record->upload_enabled}}" @if ($record->voting_enabled) disabled @endif class="change-competition-upload btn @defaultButtonSize @if ($record->upload_enabled == 1)btn-success @else btn-outline-secondary @endif" title="{{trans('partymeister-competitions::backend/competitions.upload_enabled')}}">UPL</button>
<button type="button" data-toggle="tooltip" data-placement="top" data-record="{{$record->id}}" data-class="btn-success" data-class-alternate="btn-outline-secondary" data-voting-enabled="{{(int)!$record->voting_enabled}}" @if ($record->upload_enabled) disabled @endif class="change-competition-voting btn @defaultButtonSize @if ($record->voting_enabled == 1)btn-success @else btn-outline-secondary @endif" title="{{trans('partymeister-competitions::backend/competitions.voting_enabled')}}">VOTE</button>
