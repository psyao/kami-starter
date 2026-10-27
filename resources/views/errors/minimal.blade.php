{{-- Every error page extends this layout, Laravel's own 403, 404, 500… included: restyle it to the site's theme.
     It loads no Vite asset, so it still renders when the build or the application is broken. --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="robots" content="noindex">

        <title>@yield('title')</title>

        <style>
            body { margin: 0; min-height: 100vh; display: grid; place-items: center; font-family: system-ui, sans-serif; color: #18181b; background: #fff; }
            main { padding: 1.5rem; text-align: center; }
            h1 { margin: 0; font-size: 1.125rem; font-weight: 500; }
            @media (prefers-color-scheme: dark) { body { color: #fafafa; background: #18181b; } }
        </style>
    </head>
    <body>
        <main>
            <h1>@yield('code') · @yield('message')</h1>
        </main>
    </body>
</html>
