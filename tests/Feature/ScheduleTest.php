<?php

declare(strict_types=1);

it('schedules Kami\'s tasks from routes/console.php', function (): void {
    $this->artisan('schedule:list')->expectsOutputToContain('kami:sitemap')->assertSuccessful();
});
