# Kami starter kit

A Laravel application wired for [Psyao Kami](https://github.com/psyao/kami), the Laravel and Filament CMS at home on shared hosting. Once created, the site owns this code: nothing is ever synced back from here, and everything existing sites must receive ships in the `psyao/kami` package.

## Create a site

Requirements: PHP 8.5 with the extensions [Kami lists](https://github.com/psyao/kami/blob/main/docs/guide/installation.md#requirements), Composer, and, on the developer's machine only, Node for the front-end build and [gitleaks](https://github.com/gitleaks/gitleaks) for the commit hook (`brew install gitleaks`).

```bash
laravel new site --using=psyao/kami-starter
cd site
php artisan kami:install
php artisan make:filament-user
```

The admin is at `/admin`, for the User `make:filament-user` creates. To put the site online and deploy it again, follow [Kami's deployment guide](https://github.com/psyao/kami/blob/main/docs/guide/deployment.md). Accept Laravel Boost when the installer offers it, or run `php artisan boost:install` later: either gives a coding agent Laravel's, Filament's and Kami's guidelines and skills.

## What it wires

- `app/Providers/Filament/AdminPanelProvider.php`: the panel, with `KamiPlugin`, strict authorization and Kami's local avatar provider.
- `app/Models/User.php`: panel access for every User until Kami's Roles land.
- `public/.htaccess`: no trailing-slash redirect before Laravel, so a former URL costs one 301.
- `resources/views/errors/`: one layout for every error page, without Vite assets.
- `.github/workflows/tests.yml`: Pint, Larastan, Rector and the tests on every pull request, and the Vite + Tailwind build as an artifact, so the server never runs Node.
- `boost.json`: Kami's and Filament's guidelines and skills, preselected for `boost:install`, which the installer runs without prompting for them.
- `lefthook.yml`, `pint.json`, `rector.php`, `phpstan.neon.dist`: Kami's rules and level, from the package. Hooks install with `composer install` once the site is a Git repository.
- `tests/ArchTest.php`: Filament stays in `app/Filament`, `app/Providers/Filament` and the `User` model (its `FilamentUser` contract), so a Filament major touches only them.

`composer analyse` runs Larastan, `composer test` the tests.
