{{-- The News Archive, rendered by Kami at `/news` and `/en/news` (App\Content\News::$archive): newest first, ten a page.
     Pages after the first carry a cursor and ask search engines not to index them. --}}
<x-layouts.site>
    <h1 class="text-3xl font-semibold">{{ __('kami-types.news.plural') }}</h1>

    <ul class="mt-6 space-y-4">
        @foreach ($entries as $entry)
            <li>
                <a href="{{ $entry->url() }}" class="inline-block py-1 font-medium underline underline-offset-4">{{ $entry->title }}</a>
                <p class="text-sm text-zinc-700 dark:text-zinc-300"><x-kami::date :value="$entry->published_at" /></p>
            </li>
        @endforeach
    </ul>

    <x-kami::pagination :paginator="$entries" class="mt-8" />
</x-layouts.site>
