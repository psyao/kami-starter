<?php

declare(strict_types=1);

use App\Content\News;
use Database\Seeders\DemoSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Psyao\Kami\Content\Actions\SaveEntry;

uses(RefreshDatabase::class);

// The built stylesheet, not `withoutVite()`: colour contrast and target sizes depend on it (`npm run build` first).
// Nine News besides the demo's two: the Archive's first page shows its pagination.
beforeEach(function (): void {
    $this->freezeTime();
    $this->seed(DemoSeeder::class);

    foreach (range(1, 9) as $day) {
        resolve(SaveEntry::class)->handle(News::class, ['title' => "Actualité {$day}", 'status' => 'published', 'published_at' => now()->subDays($day + 1)]);
    }
});

it('passes axe at its strictest level, light, dark and on a phone, skip link focused or not', function (string $path): void {
    foreach ([visit($path), visit($path)->inDarkMode(), visit($path)->on()->mobile()] as $page) {
        assertAxeClean($page);
        $page->keys('html > body', 'Tab')
            ->assertScript("document.activeElement.getAttribute('href')", '#main'); // the skip link, first in the tab order
        assertAxeClean($page);
        $page->assertNoJavaScriptErrors();
    }
})->with([
    'French, the default Locale' => '/',
    'English' => '/en',
    'the News Archive' => '/news',
]);
