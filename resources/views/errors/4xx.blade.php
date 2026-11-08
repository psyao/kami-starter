{{-- A 4xx in the site's layout: 403, 404, 410, 419 and 429 include it with their title, any other 4xx without a page
     of its own lands here. Never the exception's message: it may hold internals or what the visitor sent. --}}
@php($title ??= __(Symfony\Component\HttpFoundation\Response::$statusTexts[$exception->getStatusCode()] ?? 'Error'))

<x-layouts.site :title="$title">
    <h1 class="text-2xl font-semibold">{{ $exception->getStatusCode() }} · {{ $title }}</h1>
</x-layouts.site>
