{{-- A news item, rendered by Kami at its Path (App\Content\News). A blank Field shows the default Locale's value,
     marked with that Locale's lang so screen readers pronounce it right. --}}
<x-layouts.site :title="$entry->title">
    <article>
        <h1 class="text-3xl font-semibold">{{ $entry->title }}</h1>

        <p class="mt-2 text-sm text-zinc-700 dark:text-zinc-300"><x-kami::date :value="$entry->published_at" /></p>

        <p class="mt-6 whitespace-pre-line leading-relaxed" @if ($lang = $entry->fallbackLocale('body'))lang="{{ $lang }}"@endif>{{ $entry->body }}</p>
    </article>
</x-layouts.site>
