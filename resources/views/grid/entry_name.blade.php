@if ($record->remote_type != '')<span class="badge badge-danger">{{$record->remote_type}}</span> @endif<a href="" class="show-entry-description" data-id="{{$record->id}}">{{$record->title}}</a>
<br>
by {{$record->author}}

<ul class="list-unstyled">
    @foreach ($record->ordered_files as $index => $file)
        @if ($loop->first)
            <li><a style="color: green;" title="Uploaded at {{$file->created_at}}" href="{{$file->getUrl()}}">Download: {{$file->file_name}} (newest) <strong>(V{{ count($record->ordered_files) - $index}})</strong> @if ($record->final_file_media_id == $file->id) (final file) @endif</a></li>
        @else
            <li><a title="Uploaded at {{$file->created_at}}" href="{{$file->getUrl()}}">Download: {{$file->file_name}} <strong>(V{{ count($record->ordered_files) - $index}})</strong> @if ($record->final_file_media_id == $file->id) (final file) @endif</a></li>
        @endif
    @endforeach
</ul>
