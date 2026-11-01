{{-- A Page, rendered by Kami at its Path (App\Content\Page). A blank Field shows the default Locale's value,
     marked with that Locale's lang so screen readers pronounce it right. --}}
<x-layouts.site :title="$entry->title">
    <article>
        <h1 class="text-3xl font-semibold">{{ $entry->title }}</h1>

        <p class="mt-6 whitespace-pre-line leading-relaxed" @if ($lang = $entry->fallbackLocale('body'))lang="{{ $lang }}"@endif>{{ $entry->body }}</p>

        @if ($entry->tags->isNotEmpty())
            <ul class="mt-6 flex flex-wrap gap-2 text-sm" aria-label="{{ __('kami-types.page.fields.tags.label') }}">
                @foreach ($entry->tags as $tag)
                    <li>{{ $tag->title }}</li>
                @endforeach
            </ul>
        @endif
    </article>
</x-layouts.site>
