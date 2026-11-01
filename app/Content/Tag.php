<?php

declare(strict_types=1);

namespace App\Content;

use Psyao\Kami\Content\Taxonomy;

/**
 * A Page's Tags: one name for every Locale, and no address of their own.
 */
final class Tag extends Taxonomy
{
    #[\Override]
    protected bool $translatable = false;

    #[\Override]
    protected bool $routable = false;
}
