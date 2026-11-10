<?php

declare(strict_types=1);

use Database\Seeders\DemoSeeder;
use Illuminate\Cache\RateLimiter as Limiter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Testing\TestResponse;
use Livewire\Livewire;
use Psyao\Kami\Forms\Submission;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->withoutVite();
    Livewire::flushState();
    // The suite's file cache outlives a run: the Form's throttle would count every earlier run's Submissions.
    RateLimiter::swap(new Limiter(Cache::store('array')));
    $_SERVER['KAMI_ADMIN_PASSWORD'] = 'correct-horse-battery';
    $this->artisan('kami:install', ['--no-interaction' => true, '--email' => 'ada@example.test', '--name' => 'Ada'])->assertSuccessful();
    $this->seed(DemoSeeder::class);
});

afterEach(function (): void {
    unset($_SERVER['KAMI_ADMIN_PASSWORD']);
});

/**
 * A browser's POST from the home page at `$path`, with the `_kami_origin` its form carries: a valid message unless overridden.
 *
 * @param  array<string, string>  $values
 */
function sendContact(string $path, array $values = []): TestResponse
{
    $origin = html(test()->get($path)->assertOk()->getContent())->querySelector('input[name="_kami_origin"]')?->getAttribute('value');

    return test()->withHeaders(['Sec-Fetch-Site' => 'same-origin'])->post('/kami/forms/contact', [
        '_kami_origin' => $origin,
        'name' => 'Grace Hopper',
        'email' => 'grace@example.com',
        'message' => 'Hello, I would like to talk.',
        ...$values,
    ]);
}

it('renders the contact form on the home page, each field labelled', function (string $path, string $label): void {
    $html = html($this->get($path)->assertOk()->getContent());

    expect($html->querySelector('form#contact')?->getAttribute('action'))->toBe('/kami/forms/contact')
        ->and($html->querySelector('label[for="email"]')?->textContent)->toBe($label)
        ->and($html->querySelectorAll('#name[required], #email[type="email"][required], textarea#message[required]'))->toHaveCount(3)
        ->and($html->querySelector('[aria-invalid], .kami-form-errors'))->toBeNull();
})->with([
    'in French' => ['/', 'Adresse e-mail'],
    'in English' => ['/en', 'Email address'],
]);

it('stores a valid message and confirms it on the page', function (): void {
    sendContact('/en')->assertStatus(303)->assertRedirect(config('kami.sites.default.url') . '/en?sent=contact#contact');

    expect(Submission::query()->sole()->only(['locale', 'form', 'data']))->toBe([
        'locale' => 'en',
        'form' => 'contact',
        'data' => ['name' => 'Grace Hopper', 'email' => 'grace@example.com', 'message' => 'Hello, I would like to talk.'],
    ]);

    $html = html($this->get('/en?sent=contact')->assertOk()->getContent());
    expect($html->querySelector('#contact[role="status"]')?->textContent)->toBe('Thank you, your message is sent.')
        ->and($html->querySelector('form#contact'))->toBeNull();
});

it('renders a refused message again in French, its errors tied to their field', function (): void {
    $response = sendContact('/', ['email' => ''])->assertStatus(422);

    $html = html($response->getContent());
    $email = $html->querySelector('#email');
    expect($html->querySelector('.kami-form-errors[role="alert"] a[href="#email"]')?->textContent)->toBe('Le champ adresse e-mail est obligatoire.')
        ->and($email?->getAttribute('aria-invalid'))->toBe('true')
        ->and($html->getElementById((string) $email?->getAttribute('aria-describedby'))?->textContent)->toBe('Le champ adresse e-mail est obligatoire.')
        ->and($html->querySelector('#name')?->getAttribute('value'))->toBe('Grace Hopper')
        ->and(Submission::query()->count())->toBe(0);
});
