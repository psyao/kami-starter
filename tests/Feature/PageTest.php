<?php

declare(strict_types=1);

use App\Content\Page;
use Dom\HTMLDocument;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Psyao\Kami\Content\Actions\SaveEntry;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->withoutVite();
    // An admin request in an earlier test leaves Livewire injecting its assets into every page of this process.
    Livewire::flushState();

    $save = app(SaveEntry::class);
    $page = $save->handle(Page::class, ['title' => 'Bienvenue', 'status' => 'published', 'body' => 'Un site <b>Kami</b>.']);
    $save->handle(Page::class, ['title' => 'Welcome', 'status' => 'published'], $page, 'en');
});

it('renders a Page in the layout in each Locale', function (string $path, string $lang, string $title): void {
    $response = $this->get($path)
        ->assertOk()
        ->assertHeader('Content-Security-Policy')
        ->assertHeaderMissing('Set-Cookie');

    $html = HTMLDocument::createFromString($response->getContent(), LIBXML_NOERROR);

    expect($html->documentElement->getAttribute('lang'))->toBe($lang)
        ->and($html->querySelector('a[href="#main"]')?->textContent)->not->toBeEmpty()
        ->and($html->querySelectorAll('header, main#main, footer'))->toHaveCount(3)
        ->and($html->querySelector('main h1')?->textContent)->toBe($title)
        ->and($html->querySelectorAll('script, style, [style]'))->toHaveCount(0);
})->with([
    'French, the default Locale' => ['/bienvenue', 'fr', 'Bienvenue'],
    'English' => ['/en/welcome', 'en', 'Welcome'],
]);

it('escapes the body and marks its Fallback with lang', function (): void {
    $html = HTMLDocument::createFromString($this->get('/en/welcome')->getContent(), LIBXML_NOERROR);
    $body = $html->querySelector('main [lang="fr"]');

    expect($body?->textContent)->toBe('Un site <b>Kami</b>.')
        ->and($html->querySelector('main b'))->toBeNull();
});
