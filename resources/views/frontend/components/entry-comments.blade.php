<h4>Messages for entry {{$record->name}}</h4>
@if($comments->count() > 0)
    <div class="card">
        @foreach ($comments as $comment)
            <div class="card-divider">
                @if ($comment->author != '')
                    <div class="float-right">{{$comment->author}}
                        on {{date('Y-m-d H:i', strtotime($comment->created_at))}}</div>
                @else
                    <div class="float-left">{{$visitor->name}}
                        on {{date('Y-m-d H:i', strtotime($comment->created_at))}}</div>
                @endif
            </div>
            <div class="card-section @if(!$comment->read_by_visitor) unread @endif">
                {!! nl2br(e($comment->message)) !!}
            </div>
        @endforeach
    </div>
@endif
{!! form_start($entryCommentForm) !!}
<div class="card">
    @if ($comments->where('read_by_visitor', false)->count() > 0)
    <div class="card-divider">
        <button type="submit" class="button warning small expanded mark-as-read">Mark all as read</button>
    </div>
    @endif
    <div class="card-section">
        {!! form_row($entryCommentForm->message) !!}
        {!! form_row($entryCommentForm->mark_as_read) !!}
        {!! form_row($entryCommentForm->submit) !!}
    </div>
</div>
{!! form_end($entryCommentForm, false) !!}

@section('view-scripts')
    <script type="module">
        $(document).ready(function () {
            $('.mark-as-read').on('click', function (e) {
                $('input#mark_as_read').val(1);
            });
        });
    </script>
@append