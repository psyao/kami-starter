{{-- The home page: replace it with your own. Kami's Pages sit at their own Paths, as /about and /en/about. --}}
<x-layouts.site>
    <h1 class="text-3xl font-semibold">{{ config('app.name') }}</h1>

    <p class="mt-6 leading-relaxed">
        {{ __('Kami is installed. A Page you create and publish in the admin appears at its own address.') }}
    </p>

    <p class="mt-4">
        <a href="{{ url('/admin') }}" class="underline underline-offset-4">{{ __('Go to the admin') }}</a>
    </p>
</x-layouts.site>
