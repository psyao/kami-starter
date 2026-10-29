{{-- The site's layout. Kami's front CSP blocks every inline script, <style> and style attribute: styles come from
     resources/css/app.css through Vite, and the page needs no JavaScript. --}}
@props(['title' => null])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@if ($title){{ $title }} · @endif{{ config('app.name') }}</title>

        @vite('resources/css/app.css')
    </head>
    <body class="flex min-h-screen flex-col bg-white font-sans text-zinc-900 antialiased dark:bg-zinc-900 dark:text-zinc-50">
        <a href="#main" class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:rounded focus:bg-white focus:px-4 focus:py-2 focus:text-zinc-900 focus:underline">
            {{ __('Skip to content') }}
        </a>

        <header class="border-b border-zinc-200 dark:border-zinc-700">
            <div class="mx-auto max-w-2xl px-6 py-4">
                <a href="{{ url('/') }}" class="font-semibold underline-offset-4 hover:underline">{{ config('app.name') }}</a>
            </div>
        </header>

        <main id="main" class="mx-auto w-full max-w-2xl flex-1 px-6 py-12">
            {{ $slot }}
        </main>

        <footer class="border-t border-zinc-200 dark:border-zinc-700">
            <div class="mx-auto max-w-2xl px-6 py-4 text-sm text-zinc-700 dark:text-zinc-300">
                {{ config('app.name') }}
            </div>
        </footer>
    </body>
</html>
