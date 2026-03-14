<h4 class="text-lg font-bold mb-4">Messages for entry {{$record->name}}</h4>
@if($comments->count() > 0)
    <div class="card bg-base-200 shadow-md mb-4">
        @foreach ($comments as $comment)
            <div class="card-title bg-base-300 p-4 text-sm">
                @if ($comment->author != '')
                    <div class="text-right">{{$comment->author}}
                        on {{date('Y-m-d H:i', strtotime($comment->created_at))}}</div>
                @else
                    <div class="text-left">{{$visitor->name}}
                        on {{date('Y-m-d H:i', strtotime($comment->created_at))}}</div>
                @endif
            </div>
            <div class="card-body @if(!$comment->read_by_visitor) bg-warning/10 font-semibold @endif">
                {!! nl2br($comment->message) !!}
            </div>
        @endforeach
    </div>
@endif
{!! form_start($entryCommentForm) !!}
<div class="card bg-base-200 shadow-md" x-data="entryComments">
    @if ($comments->where('read_by_visitor', false)->count() > 0)
    <div class="card-title bg-base-300 p-4">
        <button type="submit" class="btn btn-warning btn-sm btn-block" x-on:click="markAsRead()">Mark all as read</button>
    </div>
    @endif
    <div class="card-body">
        {!! form_row($entryCommentForm->message) !!}
        {!! form_row($entryCommentForm->mark_as_read) !!}
        {!! form_row($entryCommentForm->submit) !!}
    </div>
</div>
{!! form_end($entryCommentForm, false) !!}

@section('view-scripts')
    <script type="module">
        document.addEventListener('alpine:init', () => {
            Alpine.data('entryComments', () => ({
                markAsRead() {
                    document.getElementById('mark_as_read').value = 1;
                }
            }));
        });
    </script>
@append
