<?php

declare(strict_types=1);

namespace App\Content;

use Illuminate\Support\Str;
use Psyao\Kami\Content\ContentType;
use Psyao\Kami\Content\Entry;
use Psyao\Kami\Content\Fields\Terms;
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
            Terms::make('tags')->of(Tag::class),
        ];
    }

    /** The page's description: the start of its body, never a fallen-back one, which would describe it in another language. */
    #[\Override]
    public function summary(Entry $entry): ?string
    {
        $value = $entry->fallbackLocale('body') === null ? $entry->getAttribute('body') : null;
        $text = is_string($value) ? Str::squish($value) : '';

        return $text === '' ? null : Str::limit($text, 160);
    }
}
