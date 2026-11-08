<?php

declare(strict_types=1);

use Dom\HTMLDocument;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Vite;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    Livewire::flushState();
    config(['app.debug' => false]);
    // Neither Locale: each page's language must come from the URL, not from app.locale (en) or a previous request.
    app()->setLocale('de');
});

function errorPage(string $content): HTMLDocument
{
    return HTMLDocument::createFromString($content, LIBXML_NOERROR);
}

it('renders a 404 in the layout in the URL\'s Locale', function (string $path, string $lang, string $title): void {
    $this->withoutVite();

    $response = $this->get($path)->assertNotFound();
    $html = errorPage($response->getContent());

    expect($html->documentElement->getAttribute('lang'))->toBe($lang)
        ->and($html->querySelector('title')?->textContent)->toStartWith($title)
        ->and($html->querySelector('main h1')?->textContent)->toBe("404 · {$title}")
        ->and($html->querySelectorAll('header, main#main, footer'))->toHaveCount(3)
        ->and($html->querySelectorAll('script, style, [style]'))->toHaveCount(0);
})->with([
    'French, the default Locale' => ['/nowhere', 'fr', 'Page introuvable'],
    'English' => ['/en/nowhere', 'en', 'Not Found'],
]);

it('renders a 500 in the shell, with no build and under the CSP', function (string $path, string $lang, string $title): void {
    // No Vite manifest: the shell must not need one.
    app(Vite::class)->useBuildDirectory('missing-build');
    Route::middleware('web')->get($path, fn () => throw new RuntimeException('Secret internals'));

    $response = $this->get($path)
        ->assertInternalServerError()
        ->assertHeader('Content-Security-Policy')
        ->assertDontSee('Secret internals');
    $html = errorPage($response->getContent());

    expect($html->documentElement->getAttribute('lang'))->toBe($lang)
        ->and($html->querySelector('h1')?->textContent)->toBe("500 · {$title}")
        ->and($html->querySelector('meta[name="robots"]')?->getAttribute('content'))->toBe('noindex')
        ->and($html->querySelector('link[rel="stylesheet"]')?->getAttribute('href'))->toEndWith('/errors.css')
        ->and($html->querySelectorAll('script, style, [style]'))->toHaveCount(0);
})->with([
    'French, the default Locale' => ['/boom', 'fr', 'Erreur du serveur'],
    'English' => ['/en/boom', 'en', 'Server Error'],
]);

it('renders maintenance mode\'s 503 in the shell, in the URL\'s Locale', function (string $path, string $lang, string $title): void {
    app()->maintenanceMode()->activate([]);

    try {
        $html = errorPage($this->get($path)->assertServiceUnavailable()->getContent());
    } finally {
        app()->maintenanceMode()->deactivate();
    }

    expect($html->documentElement->getAttribute('lang'))->toBe($lang)
        ->and($html->querySelector('h1')?->textContent)->toBe("503 · {$title}")
        ->and($html->querySelector('meta[name="robots"]')?->getAttribute('content'))->toBe('noindex')
        ->and($html->querySelectorAll('script, style, [style]'))->toHaveCount(0);
})->with([
    'French, the default Locale' => ['/', 'fr', 'Service indisponible'],
    'English' => ['/en', 'en', 'Service Unavailable'],
]);
