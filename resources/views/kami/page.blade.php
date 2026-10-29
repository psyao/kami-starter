{{-- A Page, rendered by Kami at its Path (App\Content\Page). A blank Field shows the default Locale's value,
     marked with that Locale's lang so screen readers pronounce it right. --}}
<x-layouts.site :title="$entry->title">
    <article>
        <h1 class="text-3xl font-semibold">{{ $entry->title }}</h1>

        @if (filled($entry->body))
            <p class="mt-6 whitespace-pre-line leading-relaxed" @if ($lang = $entry->fallbackLocale('body'))lang="{{ $lang }}"@endif>{{ $entry->body }}</p>
        @endif
    </article>
</x-layouts.site>
