<?php

declare(strict_types=1);

use Database\Seeders\DemoSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// The built stylesheet, not `withoutVite()`: colour contrast and target sizes depend on it (`npm run build` first).
beforeEach(function (): void {
    $this->seed(DemoSeeder::class);
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
]);
