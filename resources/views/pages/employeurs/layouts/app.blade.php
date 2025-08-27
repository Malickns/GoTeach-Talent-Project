<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Employeur') - GOTeach</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/employeurs/styles.css','resources/js/app.js','resources/js/employeurs/script.js'])
    @stack('head')
</head>
<body>
    @include('pages.employeurs.partials.header')

    @yield('content')

    @include('pages.employeurs.partials.footer')
    @stack('scripts')
</body>
</html>


