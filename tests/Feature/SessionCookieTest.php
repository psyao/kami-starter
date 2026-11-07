<?php

declare(strict_types=1);

$secure = fn (): mixed => (require config_path('session.php'))['secure'];

// A developer's .env or shell may set SESSION_SECURE_COOKIE: hide it, then put it back.
beforeEach(function (): void {
    $this->saved = [$_SERVER, $_ENV, getenv('SESSION_SECURE_COOKIE')];
    unset($_SERVER['SESSION_SECURE_COOKIE'], $_ENV['SESSION_SECURE_COOKIE']);
    putenv('SESSION_SECURE_COOKIE');
});
afterEach(function (): void {
    [$_SERVER, $_ENV, $cookie] = $this->saved;
    putenv($cookie === false ? 'SESSION_SECURE_COOKIE' : 'SESSION_SECURE_COOKIE=' . $cookie);
});

it('sends the session cookie as Secure in production', function () use ($secure): void {
    $_SERVER['APP_ENV'] = 'production';

    expect($secure())->toBeTrue();
});

it('lets SESSION_SECURE_COOKIE override the production default', function () use ($secure): void {
    $_SERVER['APP_ENV'] = 'production';
    $_SERVER['SESSION_SECURE_COOKIE'] = 'false';

    expect($secure())->toBeFalse();
});
