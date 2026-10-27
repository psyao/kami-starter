<?php

declare(strict_types=1);

it('installs Kami on a fresh project and serves the admin login page', function (): void {
    $this->artisan('kami:install', ['--no-interaction' => true])->assertSuccessful();
    // The same command serves every later deploy.
    $this->artisan('kami:install', ['--no-interaction' => true])->assertSuccessful();

    $this->get('/admin/login')->assertOk();
});
