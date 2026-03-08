@extends('motor-backend::layouts.backend')

@section('view_scripts')
    <style type="text/css">
        .unread {
            background-color: #ff9fb1;
        }
    </style>
@endsection
@section('htmlheader_title')
    {{ trans('motor-backend::backend/global.home') }}
@endsection

@section('contentheader_title')
    Messages for entry {{$record->name}}
    @if (has_permission('entries.write'))
        {!! link_to_route('backend.entries.index', trans('motor-backend::backend/global.back'), [], ['class' => 'pull-right float-right btn btn-sm btn-danger']) !!}
    @endif
@endsection

@section('main-content')
    @if($comments->count() > 0)
        <div class="@boxWrapper box-primary">
            @foreach ($comments as $comment)
                <div class="@boxHeader with-border">
                    @if ($comment->author != '')
                        <div class="float-left">{{$comment->author}}
                            on {{date('Y-m-d H:i', strtotime($comment->created_at))}}</div>
                    @else
                        <div class="float-right">{{$comment->visitor->name}}
                            on {{date('Y-m-d H:i', strtotime($comment->created_at))}}</div>
                    @endif
                </div>
                <div class="@boxBody @if(!$comment->read_by_organizer) unread @endif" style="@if ($comment->author == '') background-color: #e1e1e1 @endif">
                    {!! nl2br($comment->message) !!}
                </div>
            @endforeach
        </div>
    @endif
    {!! form_start($form) !!}
    <div class="@boxWrapper box-primary">
        <div class="@boxBody">
            @if ($comments->where('read_by_organizer', false)->count() > 0)
            <p>
                <button type="submit" class="btn btn-block btn-warning mark-as-read">Mark all as read</button>
            </p>
            @endif
            {!! form_row($form->message) !!}
            {!! form_row($form->mark_as_read) !!}
        </div>
        <div class="@boxFooter">
            {!! form_row($form->submit) !!}
        </div>
    </div>
    {!! form_end($form, false) !!}
@endsection
@section('view_scripts')
    <script type="module">
        $(document).ready(function(){
            $('.mark-as-read').on('click', function(e) {
                $('input#mark_as_read').val(1);
            });
        });
    </script>
@append