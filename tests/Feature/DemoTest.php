<?php

declare(strict_types=1);

use App\Content\Home;
use App\Models\User;
use Database\Seeders\DemoSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use Psyao\Kami\Content\Actions\SaveEntry;
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

it('leaves a Home an editor published alone', function (): void {
    $home = Entry::query()->withUnpublished()->ofType(Home::class)->first();
    resolve(SaveEntry::class)->handle(Home::class, ['title' => 'Edited', 'status' => 'published'], $home);

    $this->seed(DemoSeeder::class);

    $this->get('/')->assertOk()->assertSee('Edited');
});

it('leaves kami:doctor nothing to report on the types it declares', function (): void {
    // The administrator's MFA, which the doctor otherwise warns is not set up yet.
    withMfa(User::query()->sole());
    // The doctor's header probe would reach whatever serves APP_URL on this machine.
    Http::fake(['*' => Http::failedConnection()]);

    $this->artisan('kami:doctor', ['--strict' => true])->assertSuccessful();
});

it('keeps a seeded page within its weight budget', function (): void {
    // The HTML half of the page-weight budget; CI checks the CSS half on the build.
    $this->seed(DemoSeeder::class);

    expect(mb_strlen(gzencode($this->get('/about')->assertOk()->getContent(), 9), '8bit'))->toBeLessThanOrEqual(30 * 1024);
});
