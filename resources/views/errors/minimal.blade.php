{{-- The 5xx shell, Laravel's own 500 and 503 included (and its 401 and 402): restyle it to the site's theme. A 500
     may mean the database or the build is broken, so it reads neither: no x-kami::seo, no Vite asset. Its styles
     sit in public/errors.css, as Kami's CSP blocks an inline <style>. Maintenance mode's 503 answers before any
     route, with no security header: keep it static. --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="robots" content="noindex">

        <title>@yield('title')</title>

        <link rel="stylesheet" href="{{ asset('errors.css') }}">
    </head>
    <body>
        <main>
            <h1>@yield('code') · @yield('message')</h1>
        </main>
    </body>
</html>
