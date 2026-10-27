# Kami starter kit

A Laravel application wired for [Kami](https://github.com/psyao/kami), the Laravel and Filament CMS at home on shared hosting. Once created, the site owns this code: nothing is ever synced back from here, and everything existing sites must receive ships in the `psyao/kami` package.

## Create a site

Requirements: PHP 8.5 with the extensions [Kami lists](https://github.com/psyao/kami/blob/main/docs/guide/installation.md#requirements), Composer, and Node for local front-end work only.

```bash
laravel new site --using=psyao/kami-starter
cd site
php artisan kami:install
```

The admin is at `/admin`. `kami:install` also serves every later deploy: run it after each one.

## What it wires

- `app/Providers/Filament/AdminPanelProvider.php`: the panel, with `KamiPlugin`, strict authorization and Kami's local avatar provider.
- `app/Models/User.php`: panel access for every User until Kami's Roles land.
- `public/.htaccess`: no trailing-slash redirect before Laravel, so a former URL costs one 301.
- `resources/views/errors/`: one layout for every error page, without Vite assets.
- `.github/workflows/tests.yml`: Pint, Larastan, Rector and the tests on every pull request, and the Vite + Tailwind build as an artifact, so the server never runs Node.
- `lefthook.yml`, `pint.json`, `rector.php`, `phpstan.neon.dist`: Kami's rules and level, from the package. Hooks install with `composer install` once the site is a Git repository.
- `tests/ArchTest.php`: Filament stays in `app/Filament` and `app/Providers/Filament`, so a Filament major touches only them.

`composer analyse` runs Larastan, `composer test` the tests.
