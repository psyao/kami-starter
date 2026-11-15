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

        // Two News items a day apart, which their Archive lists newest first.
        $news = $save->handle(News::class, [
            'title' => 'Kami est installé',
            'status' => 'published',
            'published_at' => now()->subDay(),
            'body' => 'Une Actualité : son Archive les liste de la plus récente à la plus ancienne.',
        ]);
        $save->handle(News::class, [
            'title' => 'Kami is installed',
            'status' => 'published',
            'published_at' => now()->subDay(),
            'body' => 'A News item: its Archive lists them newest first.',
        ], $news, 'en');

        $news = $save->handle(News::class, [
            'title' => 'Le menu principal',
            'status' => 'published',
            'body' => 'Le menu en haut de chaque page se modifie dans l’admin.',
        ]);
        $save->handle(News::class, [
            'title' => 'The main menu',
            'status' => 'published',
            'body' => 'The menu at the top of every page is edited in the admin.',
        ], $news, 'en');

        $menu->handle('primary', ['entry' => $home, 'label' => ['fr' => 'Accueil', 'en' => 'Home']]);
        $menu->handle('primary', ['entry' => $page]);
        $menu->handle('primary', ['archive' => 'news']);
    }
}
