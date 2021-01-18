<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Webartisan</title>
    <link href="{{ asset('solumdesignum/webartisan/jquery.terminal.css') }}" rel="stylesheet"/>

    <link href="{{ asset('solumdesignum/webartisan/webartisan.css') }}" rel="stylesheet"/>

    <script>
    var WebArtisanEndpoint  = "{{ url('artisan/run') }}";
    var exitUrl             = "{{ url('') }}";
    var greetings           = 'Laravel Web Artisan';
    </script>
</head>
<body>
    <div id="webartisan"></div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0-alpha1/jquery.min.js"></script>
    <script src="{{ asset('solumdesignum/webartisan/jquery.terminal-0.8.8.min.js') }}"></script>
    <script src="{{ asset('solumdesignum/webartisan/app.js') }}"></script>
</body>
</html>
