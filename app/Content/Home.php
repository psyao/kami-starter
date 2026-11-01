<?php

declare(strict_types=1);

namespace App\Content;

use Psyao\Kami\Content\Fields\Textarea;
use Psyao\Kami\Content\Singleton;

/**
 * The home page, one Entry at `/` and `/en`, rendered by `resources/views/kami/home.blade.php`.
 */
final class Home extends Singleton
{
    #[\Override]
    protected ?string $prefix = '';

    public function fields(): array
    {
        return [
            Textarea::make('intro'),
        ];
    }
}
