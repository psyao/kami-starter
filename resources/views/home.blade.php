{{-- The home page: replace it with your own. Kami's Pages sit at their own Paths, as /about and /en/about. --}}
<x-layouts.site>
    <h1 class="text-3xl font-semibold">{{ config('app.name') }}</h1>

    <p class="mt-6 leading-relaxed">
        Kami is installed. Create a Page in the <a href="{{ url('/admin') }}" class="underline underline-offset-4">admin</a>: it appears at its own address.
    </p>
</x-layouts.site>
