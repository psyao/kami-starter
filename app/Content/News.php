<?php

declare(strict_types=1);

namespace App\Content;

use Illuminate\Support\Str;
use Psyao\Kami\Content\ContentType;
use Psyao\Kami\Content\Entry;
use Psyao\Kami\Content\Fields\Textarea;

/**
 * A news item at `/news/{slug}`, rendered by `resources/views/kami/news.blade.php`. Its Archive lists them newest first,
 * ten a page, at `/news` and `/en/news`, rendered by `resources/views/kami/news-archive.blade.php`.
 */
final class News extends ContentType
{
    #[\Override]
    protected bool $archive = true;

    public function fields(): array
    {
        return [
            Textarea::make('body'),
        ];
    }

    /** The item's description: the start of its body, never a fallen-back one, which would describe it in another language. */
    #[\Override]
    public function summary(Entry $entry): ?string
    {
        $value = $entry->fallbackLocale('body') === null ? $entry->getAttribute('body') : null;
        $text = is_string($value) ? Str::squish($value) : '';

        return $text === '' ? null : Str::limit($text, 160);
    }
}
