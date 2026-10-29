<?php

declare(strict_types=1);

use App\Content\Page;

return [

    /*
    | The site's Content Types: `final` classes extending `Psyao\Kami\Content\ContentType` (ADR 0007).
    */
    'types' => [
        Page::class,
    ],

    /*
    | The Roles a User may hold, by handle, each with its Permissions; `*` matches any part (`kami.*`, `kami.entries.*.view`).
    | Checks always target a Permission, never a Role (ADR 0014). Labels come from the `kami-roles` translation file.
    */
    'roles' => [
        'administrator' => ['kami.*'],
    ],

    /*
    | The Sites this installation serves, by handle. Each has its URL, its Locales (the first is the default)
    | and the time zone its dates are displayed in; dates are stored in UTC (ADR 0004).
    */
    'sites' => [
        'default' => [
            'url' => env('APP_URL', 'http://localhost'),
            'locales' => ['fr', 'en'],
            'timezone' => 'Europe/Zurich',
        ],
    ],

    /*
    | The front's Content Security Policy and headers (ADR 0018); the admin profile is fixed. Every option that weakens
    | them is off by default and raises a `kami:doctor` warning when on (docs/guide/security.md#weakening-options).
    |
    | `csp`: sources added per directive, merged with Kami's and never replacing them (`'img-src' => ['https://cdn.example.com']`).
    | `headers`: a companion header replaced by value, or omitted when set to `null`.
    | `csp_report_uri`: where browsers send violation reports, with the page URL and the visitor's address.
    | `csp_report_only`: reports violations without blocking them.
    */
    'security' => [
        'csp' => [],
        'headers' => [],
        'csp_report_uri' => null,
        'csp_report_only' => false,
    ],

];
