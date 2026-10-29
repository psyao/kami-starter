{{-- Every error page extends this layout, Laravel's own 403, 404, 500… included: restyle it to the site's theme.
     It loads no Vite asset, so it still renders when the build or the application is broken. Its styles sit in
     public/errors.css: Kami's CSP blocks an inline <style>. --}}
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
