<?php

declare(strict_types=1);

namespace App\Content;

use Illuminate\Support\Str;
use Psyao\Kami\Content\Entry;
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

    /** The page's description: the start of its intro, never a fallen-back one, which would describe it in another language. */
    #[\Override]
    public function summary(Entry $entry): ?string
    {
        $value = $entry->fallbackLocale('intro') === null ? $entry->getAttribute('intro') : null;
        $text = is_string($value) ? Str::squish($value) : '';

        return $text === '' ? null : Str::limit($text, 160);
    }
}
