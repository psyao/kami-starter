<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Content\Home;
use App\Content\News;
use App\Content\Page;
use App\Content\Tag;
use Illuminate\Database\Seeder;
use Psyao\Kami\Content\Actions\SaveEntry;
use Psyao\Kami\Content\Entry;
use Psyao\Kami\Menus\Actions\SaveMenuItem;

/**
 * A demo of the content model, written as any seeder or import should: through `SaveEntry`.
 *
 * Publishes the Home that `kami:install` created as a Draft, a Page with two Tags and two News items, in both Locales, and fills
 * the primary Menu with them. Delete it when the site has its own content.
 */
final class DemoSeeder extends Seeder
{
    public function run(SaveEntry $save, SaveMenuItem $menu): void
    {
        // A second run would refuse the Page's slug.
        if (Entry::query()->withUnpublished()->ofType(Page::class)->exists()) {
            return;
        }

        $home = Entry::query()->withUnpublished()->ofType(Home::class)->first();

        // A Home already published is an editor's: the demo leaves it alone.
        if ($home?->status !== 'published') {
            $home = $save->handle(Home::class, [
                'title' => 'Bienvenue',
                'status' => 'published',
                'intro' => 'Kami est installé. Cette page est un Singleton : modifiez-la dans l’admin.',
            ], $home);
            $save->handle(Home::class, [
                'title' => 'Welcome',
                'status' => 'published',
                'intro' => 'Kami is installed. This page is a Singleton: edit it in the admin.',
            ], $home, 'en');
        }

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

        foreach ([
            ['Kami est installé', 'Kami is installed', 'Une Actualité : son Archive les liste de la plus récente à la plus ancienne.', 'A News item: its Archive lists them newest first.', now()->subDay()],
            ['Le menu principal', 'The main menu', 'Le menu en haut de chaque page se modifie dans l’admin.', 'The menu at the top of every page is edited in the admin.', now()],
        ] as [$fr, $en, $frBody, $enBody, $publishedAt]) {
            $news = $save->handle(News::class, ['title' => $fr, 'status' => 'published', 'published_at' => $publishedAt, 'body' => $frBody]);
            $save->handle(News::class, ['title' => $en, 'status' => 'published', 'published_at' => $publishedAt, 'body' => $enBody], $news, 'en');
        }

        $menu->handle('primary', ['entry' => $home, 'label' => ['fr' => 'Accueil', 'en' => 'Home']]);
        $menu->handle('primary', ['entry' => $page]);
        $menu->handle('primary', ['archive' => 'news']);
    }
}
