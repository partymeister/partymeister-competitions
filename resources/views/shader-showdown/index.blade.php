<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Shader Showdown — Live Voting Control</title>
    <link rel="preconnect" href="https://fonts.bunny.net" crossorigin>
    <link href="https://fonts.bunny.net/css?family=source-sans-3:400,600,700|quattrocento-sans:400,700" rel="stylesheet">
</head>
<body>
<div id="app"></div>

@if(app()->environment('local') && file_exists(public_path('hot-shader-showdown')))
    <script type="module" src="http://localhost:5178/@vite/client"></script>
    <script type="module" src="http://localhost:5178/main.ts"></script>
@else
    @php
        $manifest = json_decode(file_get_contents(public_path('build/shader-showdown/.vite/manifest.json')), true);
        $entry = $manifest['main.ts'] ?? null;
    @endphp
    @if($entry)
        @if(isset($entry['css']))
            @foreach($entry['css'] as $css)
                <link rel="stylesheet" href="/build/shader-showdown/{{ $css }}" />
            @endforeach
        @endif
        <script type="module" src="/build/shader-showdown/{{ $entry['file'] }}"></script>
    @endif
@endif
</body>
</html>
