@if ($record->voting_enabled)
    @if ($record->all_final_files_confirmed)
        <span class="badge badge-success" data-toggle="tooltip" data-placement="top" title="{{trans('partymeister-competitions::backend/competitions.all_final_files_confirmed')}}">REL</span>
    @else
        @if (config('partymeister-competitions.require_all_final_files_for_release', false))
            <span class="badge badge-danger" data-toggle="tooltip" data-placement="top" title="{{trans('partymeister-competitions::backend/competitions.final_files_missing_blocked')}}">REL</span>
        @else
            <span class="badge badge-warning" data-toggle="tooltip" data-placement="top" title="{{trans('partymeister-competitions::backend/competitions.final_files_missing')}}">REL</span>
        @endif
    @endif
@endif
