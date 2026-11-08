{{-- A 4xx in the site's layout: 403, 404, 419 and 429 include it (to win over Laravel's own views), any other 4xx
     without a page of its own lands here. Never the exception's message: it may hold internals or what the visitor
     sent. The layout needs the Vite build: without one, Laravel answers its bare page, with the same status. --}}
@php($title ??= __(Symfony\Component\HttpFoundation\Response::$statusTexts[$exception->getStatusCode()] ?? 'Error'))

<x-layouts.site :title="$title">
    <h1 class="text-2xl font-semibold">{{ $exception->getStatusCode() }} · {{ $title }}</h1>
</x-layouts.site>
