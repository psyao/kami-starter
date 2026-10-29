# Kami starter kit

A Laravel application wired for [Psyao Kami](https://github.com/psyao/kami), the Laravel and Filament CMS at home on shared hosting. Once created, the site owns this code: nothing is ever synced back from here, and everything existing sites must receive ships in the `psyao/kami` package.

## Create a site

Requirements: PHP 8.5 with the extensions [Kami lists](https://github.com/psyao/kami/blob/main/docs/guide/installation.md#requirements), Composer, and, on the developer's machine only, Node for the front-end build and [gitleaks](https://github.com/gitleaks/gitleaks) for the commit hook (`brew install gitleaks`).

```bash
laravel new site --using=psyao/kami-starter
cd site
php artisan kami:install
```

`kami:install` asks for the first administrator, who signs in at `/admin`; so does `composer setup`, which also builds the front. Without a terminal, pass the administrator's details as Kami's [installation guide](https://github.com/psyao/kami/blob/main/docs/guide/installation.md#the-first-user) shows. Create a Page in the admin and publish it: it answers at its own address, `/about` in French and `/en/about` in English.

Until Kami 0.2.0, the starter kit requires Kami's `main` branch (`"psyao/kami": "dev-main"`): its wiring is not released yet ([psyao/kami#130](https://github.com/psyao/kami/issues/130)). Once 0.2.0 is out, change it to `"^0.2"` in your site's `composer.json` and run `composer update psyao/kami`.

To put the site online and deploy it again, follow [Kami's deployment guide](https://github.com/psyao/kami/blob/main/docs/guide/deployment.md). Accept Laravel Boost when the installer offers it, or run `php artisan boost:install` later: either gives a coding agent Laravel's, Filament's and Kami's guidelines and skills.

## What it wires

- `routes/web.php`: `Kami::routes()`, the fallback route that serves every Entry at its Path, after the site's own routes.
- `bootstrap/app.php`: Kami's front security headers and CSP on the `web` group too.
- `app/Providers/Filament/AdminPanelProvider.php`: the panel, with `KamiPlugin`, strict authorization, Kami's local avatar provider and Kami's admin security headers.
- `app/Models/User.php`: `KamiUser` with `HasKamiRoles`; only a User holding a Kami Role gets into the admin.
- `config/kami.php`: the example `Page` type (`app/Content/Page.php`), two Locales (`fr`, the default, and `en`) and the `administrator` Role. `database/migrations/*_create_kami_type_page_table.php` is the Page's table, written by `kami:make:migration`.
- `lang/{en,fr}/kami-types.php`: the admin's labels for the Page; `lang/fr.json`: the layout's strings in French.
- `resources/views/components/layouts/site.blade.php`: a minimal accessible layout (`lang`, skip link, landmarks), built by Vite and Tailwind, with no JavaScript, no web font and nothing inline. `resources/views/kami/page.blade.php` renders a Page in it.
- `public/.htaccess`: no trailing-slash redirect before Laravel, so a former URL costs one 301.
- `resources/views/errors/`: one layout for every error page, without Vite assets; its styles are in `public/errors.css`.
- `.github/workflows/tests.yml`: Pint, Larastan, Rector and the tests on every pull request, and the Vite + Tailwind build as an artifact, so the server never runs Node.
- `boost.json`: Kami's and Filament's guidelines and skills, preselected for `boost:install`, which the installer runs without prompting for them.
- `lefthook.yml`, `pint.json`, `rector.php`, `phpstan.neon.dist`: Kami's rules and level, from the package. Hooks install with `composer install` once the site is a Git repository.
- `tests/ArchTest.php`: Filament stays in `app/Filament`, `app/Providers/Filament` and the `User` model (its `FilamentUser` contract), so a Filament major touches only them.

`composer analyse` runs Larastan, `composer test` the tests.
