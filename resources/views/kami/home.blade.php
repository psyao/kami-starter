{{-- The home page, rendered by Kami at `/` and `/en` (App\Content\Home): replace it with your own. --}}
<x-layouts.site :title="$entry->title">
    <h1 class="text-3xl font-semibold">{{ $entry->title }}</h1>

    <p class="mt-6 whitespace-pre-line leading-relaxed" @if ($lang = $entry->fallbackLocale('intro'))lang="{{ $lang }}"@endif>{{ $entry->intro }}</p>

    <p class="mt-4">
        <a href="{{ url('/admin') }}" class="underline underline-offset-4">{{ __('Go to the admin') }}</a>
    </p>
</x-layouts.site>
