<h4 class="text-lg font-bold mb-4">Messages for entry {{$record->name}}</h4>
@if($comments->count() > 0)
    <div class="rounded-lg bg-surface shadow-[0_2px_8px_rgba(0,0,0,0.3)] mb-4">
        @foreach ($comments as $comment)
            <div class="px-5 py-3 bg-surface-raised rounded-t-lg text-heading font-semibold text-sm border-b border-border">
                @if ($comment->author != '')
                    <div class="text-right">{{$comment->author}}
                        on {{date('Y-m-d H:i', strtotime($comment->created_at))}}</div>
                @else
                    <div class="text-left">{{$visitor->name}}
                        on {{date('Y-m-d H:i', strtotime($comment->created_at))}}</div>
                @endif
            </div>
            <div class="p-5 @if(!$comment->read_by_visitor) bg-warning/10 font-semibold @endif">
                {!! nl2br($comment->message) !!}
            </div>
        @endforeach
    </div>
@endif
{!! form_start($entryCommentForm) !!}
<div class="rounded-lg bg-surface shadow-[0_2px_8px_rgba(0,0,0,0.3)]" x-data="entryComments">
    @if ($comments->where('read_by_visitor', false)->count() > 0)
    <div class="px-5 py-3 bg-surface-raised rounded-t-lg text-heading font-semibold text-sm border-b border-border">
        <button type="submit" class="w-full inline-flex items-center justify-center rounded-lg bg-accent px-3 py-1.5 text-xs font-semibold text-body hover:bg-accent-hover transition-colors" x-on:click="markAsRead()">Mark all as read</button>
    </div>
    @endif
    <div class="p-5">
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
