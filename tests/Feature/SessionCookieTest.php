<?php

declare(strict_types=1);

$config = fn (): array => require config_path('session.php');
$hidden = ['SESSION_SECURE_COOKIE', 'SESSION_DRIVER'];

// A developer's .env, a shell or phpunit.xml may set them: hide them, then put them back.
beforeEach(function () use ($hidden): void {
    $this->saved = [$_SERVER, $_ENV, array_map(getenv(...), $hidden)];

    foreach ($hidden as $name) {
        unset($_SERVER[$name], $_ENV[$name]);
        putenv($name);
    }
});
afterEach(function () use ($hidden): void {
    [$_SERVER, $_ENV, $values] = $this->saved;

    foreach (array_combine($hidden, $values) as $name => $value) {
        putenv($value === false ? $name : "{$name}={$value}");
    }
});

it('sends the session cookie as Secure in production', function () use ($config): void {
    $_SERVER['APP_ENV'] = 'production';

    expect($config()['secure'])->toBeTrue();
});

it('lets SESSION_SECURE_COOKIE override the production default', function () use ($config): void {
    $_SERVER['APP_ENV'] = 'production';
    $_SERVER['SESSION_SECURE_COOKIE'] = 'false';

    expect($config()['secure'])->toBeFalse();
});

it('keeps sessions in files, with no IP address or browser name in the database', function () use ($config): void {
    expect($config()['driver'])->toBe('file')
        ->and(file_get_contents(base_path('.env.example')))->toContain("\nSESSION_DRIVER=file\n");
});
