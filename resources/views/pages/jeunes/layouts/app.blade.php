<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'GoTeach')</title>
    @yield('head')
</head>
<body>
    @includeIf('pages.jeunes.partials.header')
    @yield('content')
    @yield('scripts')
</body>
</html>


