<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Content\Home;
use App\Content\Page;
use App\Content\Tag;
use Illuminate\Database\Seeder;
use Psyao\Kami\Content\Actions\SaveEntry;
use Psyao\Kami\Content\Entry;

/**
 * A demo of the content model, written as any seeder or import should: through `SaveEntry`.
 *
 * Publishes the Home that `kami:install` created as a Draft, and a Page with two Tags, in both Locales. Delete it when the site has its own content.
 */
final class DemoSeeder extends Seeder
{
    public function run(SaveEntry $save): void
    {
        // A second run would refuse the Page's slug, and overwrite what editors changed on Home.
        if (Entry::query()->withUnpublished()->ofType(Page::class)->exists()) {
            return;
        }

        $home = $save->handle(Home::class, [
            'title' => 'Bienvenue',
            'status' => 'published',
            'intro' => 'Kami est installé. Cette page est un Singleton : modifiez-la dans l’admin.',
        ], Entry::query()->withUnpublished()->ofType(Home::class)->first());
        $save->handle(Home::class, [
            'title' => 'Welcome',
            'status' => 'published',
            'intro' => 'Kami is installed. This page is a Singleton: edit it in the admin.',
        ], $home, 'en');

        $tags = array_map(
            fn (string $title): string => $save->handle(Tag::class, ['title' => $title])->id,
            ['Kami', 'Demo'],
        );

        $page = $save->handle(Page::class, [
            'title' => 'À propos',
            'slug' => 'about',
            'status' => 'published',
            'body' => 'Une Page, avec ses Étiquettes dans l’ordre choisi.',
            'tags' => $tags,
        ]);
        $save->handle(Page::class, [
            'title' => 'About',
            'slug' => 'about',
            'status' => 'published',
            'body' => 'A Page, with its Tags in the order picked.',
        ], $page, 'en');
    }
}
