<?php

declare(strict_types=1);

use App\Models\User;

beforeEach(fn () => $_SERVER['KAMI_ADMIN_PASSWORD'] = 'correct-horse-battery');
afterEach(function (): void {
    unset($_SERVER['KAMI_ADMIN_PASSWORD']);
});

it('installs Kami on a fresh project with its first administrator', function (): void {
    $this->artisan('kami:install', ['--no-interaction' => true, '--email' => 'ada@example.test', '--name' => 'Ada'])
        ->assertSuccessful();
    // The same command serves every later deploy, without the administrator's details.
    $this->artisan('kami:install', ['--no-interaction' => true])->assertSuccessful();

    $ada = User::query()->sole();
    expect($ada->email)->toBe('ada@example.test')
        ->and($ada->kamiRoles())->toBe(['administrator']);

    $this->get('/admin/login')->assertOk()->assertHeader('Content-Security-Policy');
    $this->artisan('kami:doctor', ['--strict' => true])->assertSuccessful();
});

it('lets only a Kami Role holder into the admin', function (): void {
    $this->artisan('kami:install', ['--no-interaction' => true, '--email' => 'ada@example.test', '--name' => 'Ada'])
        ->assertSuccessful();

    $this->actingAs(User::query()->sole())->get('/admin')->assertOk();
    $this->actingAs(User::factory()->create())->get('/admin')->assertForbidden();
});
