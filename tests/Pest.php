<?php

declare(strict_types=1);

use App\Models\User;
use Dom\HTMLDocument;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Pest\Browser\Api\On;
use Pest\Browser\Api\PendingAwaitablePage;
use Tests\TestCase;

pest()->extend(TestCase::class)->in('Feature', 'Browser');

/** MFA set up for `$user`: a User who may manage Users is otherwise sent to set it up before any admin page, and `kami:doctor` warns. */
function withMfa(User $user): User
{
    DB::table('kami_user_mfa')->insert(['user_id' => $user->getKey(), 'secret' => Crypt::encryptString('JBSWY3DPEHPK3PXP')]);

    return $user;
}

/** A response's HTML, parsed leniently, to query it as a browser would. */
function html(string $content): HTMLDocument
{
    return HTMLDocument::createFromString($content, LIBXML_NOERROR);
}

/**
 * axe at its strictest: every impact, and the WCAG 2.2 rules `assertNoAccessibilityIssues()` leaves out (`target-size`).
 * Incomplete results fail too: a target covered by another one is only "incomplete" to axe (#35).
 */
function assertAxeClean(On | PendingAwaitablePage $page): void
{
    $page->assertNoAccessibilityIssues(3); // injects axe

    $results = $page->script(<<<'JS'
        async () => {
            const tags = ['wcag2a', 'wcag2aa', 'wcag21a', 'wcag21aa', 'wcag22aa', 'best-practice'];
            const { violations, incomplete } = await axe.run(document, { runOnly: { type: 'tag', values: tags } });
            return [...violations, ...incomplete].flatMap(r => r.nodes.map(n => `${r.id}: ${n.target.join(' ')}`));
        }
        JS);

    expect($results)->toBe([]);
}
