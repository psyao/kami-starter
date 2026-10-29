<?php

declare(strict_types=1);

namespace App\Content;

use Psyao\Kami\Content\ContentType;
use Psyao\Kami\Content\Fields\Textarea;

/**
 * A page at the root of the site, `/about` and `/en/about`, rendered by `resources/views/kami/page.blade.php`.
 */
final class Page extends ContentType
{
    #[\Override]
    protected ?string $prefix = '';

    public function fields(): array
    {
        return [
            Textarea::make('body'),
        ];
    }
}
