<?php

declare(strict_types=1);

use Database\Seeders\DemoSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Psyao\Kami\Content\Entry;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->withoutVite();
    Livewire::flushState();
    $_SERVER['KAMI_ADMIN_PASSWORD'] = 'correct-horse-battery';
    $this->artisan('kami:install', ['--no-interaction' => true, '--email' => 'ada@example.test', '--name' => 'Ada'])->assertSuccessful();
});

afterEach(function (): void {
    unset($_SERVER['KAMI_ADMIN_PASSWORD']);
});

it('leaves the home page a Draft until it is published', function (): void {
    $this->get('/')->assertNotFound();
});

it('seeds a demo answering in both Locales', function (string $path, string $text): void {
    $this->seed(DemoSeeder::class);

    $this->get($path)->assertOk()->assertSee($text);
})->with([
    'the French home page' => ['/', 'Bienvenue'],
    'the English home page' => ['/en', 'Welcome'],
    'the French Page' => ['/about', 'À propos'],
    'the English Page' => ['/en/about', 'About'],
]);

it('shows a Page\'s Tags in pick order', function (): void {
    $this->seed(DemoSeeder::class);

    $this->get('/about')->assertSeeInOrder(['<li>Kami</li>', '<li>Demo</li>'], false);
});

it('touches nothing on a second run', function (): void {
    $this->seed(DemoSeeder::class);
    $entries = Entry::query()->withUnpublished()->count();

    $this->seed(DemoSeeder::class);

    expect(Entry::query()->withUnpublished()->count())->toBe($entries);
});

it('leaves kami:doctor nothing to report on the types it declares', function (): void {
    $this->artisan('kami:doctor', ['--strict' => true])->assertSuccessful();
});
