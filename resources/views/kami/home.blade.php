{{-- The home page, rendered by Kami at `/` and `/en` (App\Content\Home): replace it with your own. --}}
@use('Psyao\Kami\Facades\Kami')
<x-layouts.site :title="$entry->title">
    <h1 class="text-3xl font-semibold">{{ $entry->title }}</h1>

    <p class="mt-6 whitespace-pre-line leading-relaxed" @if ($lang = $entry->fallbackLocale('intro'))lang="{{ $lang }}"@endif>{{ $entry->intro }}</p>

    <p class="mt-4">
        <a href="{{ url('/admin') }}" class="underline underline-offset-4">{{ __('Go to the admin') }}</a>
    </p>

    {{-- App\Forms\ContactForm; removing it from kami.forms means removing this section too. Each input's id is its name:
         the error summary links to it. No maxlength on the textarea: a browser counts a line break as one character, the
         server as two. --}}
    <section class="mt-12" aria-labelledby="contact-heading">
        <h2 id="contact-heading" class="text-2xl font-semibold">{{ Kami::form('contact')->label() }}</h2>

        @if (Kami::form('contact')->sent())
            <p id="contact" role="status" class="mt-4">{{ __('Thank you, your message is sent.') }}</p>
        @else
            <x-kami::form form="contact" class="mt-4 space-y-6">
                <x-kami::form.errors level="3" />

                <p>{{ __('All fields are required.') }}</p>

                <div>
                    <label for="name" class="block font-medium">{{ __('Name') }}</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required maxlength="100" autocomplete="name"
                        class="mt-1 w-full rounded border border-zinc-500 bg-transparent px-3 py-2"
                        @error('name') aria-invalid="true" aria-describedby="name-error" @enderror>
                    @error('name')<p id="name-error" class="mt-1 text-red-700 dark:text-red-300">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="email" class="block font-medium">{{ __('Email address') }}</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required maxlength="255" autocomplete="email"
                        class="mt-1 w-full rounded border border-zinc-500 bg-transparent px-3 py-2"
                        @error('email') aria-invalid="true" aria-describedby="email-error" @enderror>
                    @error('email')<p id="email-error" class="mt-1 text-red-700 dark:text-red-300">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="message" class="block font-medium">{{ __('Message') }}</label>
                    <textarea id="message" name="message" rows="6" required
                        class="mt-1 w-full rounded border border-zinc-500 bg-transparent px-3 py-2"
                        @error('message') aria-invalid="true" aria-describedby="message-error" @enderror>{{ old('message') }}</textarea>
                    @error('message')<p id="message-error" class="mt-1 text-red-700 dark:text-red-300">{{ $message }}</p>@enderror
                </div>

                <button type="submit" class="rounded bg-zinc-900 px-4 py-2 font-medium text-white dark:bg-zinc-50 dark:text-zinc-900">{{ __('Send') }}</button>
            </x-kami::form>
        @endif
    </section>
</x-layouts.site>
