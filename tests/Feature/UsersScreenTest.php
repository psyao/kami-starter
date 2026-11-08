<?php

declare(strict_types=1);

use App\Filament\Resources\Users\Pages\CreateUser;
use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Models\User;
use Filament\Actions\Testing\TestAction;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Features\SupportTesting\Testable;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->withoutVite();
});

/** A User holding these Roles, created without model events: a fixture, whose `kami.users.created` row no test reads. */
function userWith(string $name, string ...$roles): User
{
    $user = User::withoutEvents(fn () => User::forceCreate(['name' => $name, 'email' => Str::lower($name) . '@example.test', 'password' => 'secret']));
    DB::table('kami_role_user')->insert(array_map(fn (string $role): array => ['id' => (string) Str::ulid(), 'user_id' => $user->getKey(), 'role' => $role], $roles));

    return $user;
}

/** Signed in with MFA set up, holding the Role, or these Permissions under a Role of their own. */
function signInWith(string | array $role = 'administrator'): User
{
    if (is_array($role)) {
        config(['kami.roles.custom' => $role]);
        $role = 'custom';
    }

    $user = User::withoutEvents(fn () => User::forceCreate(['name' => 'Ada', 'email' => uniqid() . '@example.test', 'password' => 'secret']));
    DB::table('kami_role_user')->insert(['id' => (string) Str::ulid(), 'user_id' => $user->getKey(), 'role' => $role]);
    // The panel's AuthenticateSession signs out a session holding an earlier User's password hash.
    test()->flushSession()->actingAs(withMfa($user));

    return $user;
}

/** A page of the Users screen, for a User holding the Role, or these Permissions under a Role of their own. */
function usersPage(string $page, string | array $role = 'administrator', array $parameters = []): Testable
{
    signInWith($role);
    Filament::setCurrentPanel('admin');

    return Livewire::test($page, $parameters);
}

/** @return list<string> */
function auditEvents(): array
{
    return DB::table('kami_audit_events')->orderBy('occurred_at')->orderBy('id')->pluck('event')->all();
}

it('takes kami.users.manage, which administrator holds and editor does not', function (): void {
    $grace = userWith('Grace', 'editor');

    signInWith('editor');
    $this->get('/admin/users')->assertForbidden();
    $this->get("/admin/users/{$grace->id}/edit")->assertForbidden();

    signInWith(['kami.users.manage']);
    $this->get('/admin/users')->assertOk();
    $this->get('/admin/users/create')->assertOk();
    $this->get("/admin/users/{$grace->id}/edit")->assertOk();
});

it('lists every User with the labels of their Roles', function (): void {
    $grace = userWith('Grace', 'editor', 'administrator');
    $linus = userWith('Linus');

    usersPage(ListUsers::class)
        ->assertCanSeeTableRecords([$grace, $linus])
        ->assertTableColumnStateSet('roles', [__('kami-roles.administrator'), __('kami-roles.editor')], $grace)
        ->assertTableColumnStateSet('roles', null, $linus);
});

it('creates a User with Roles and no password of anyone’s choosing, offering a one-time link to set it', function (): void {
    usersPage(CreateUser::class)
        ->fillForm(['name' => 'Grace', 'email' => 'grace@example.test', 'kami_roles' => ['editor']])
        ->call('create')
        ->assertHasNoFormErrors()
        ->assertNotified(__('users.link.title', ['name' => 'Grace']));

    $grace = User::query()->where('email', 'grace@example.test')->sole();
    expect($grace->kamiRoles())->toBe(['editor'])
        ->and(DB::table('password_reset_tokens')->where('email', 'grace@example.test')->exists())->toBeTrue()
        ->and(auditEvents())->toBe(['kami.users.created', 'kami.roles.granted']);
});

it('refuses taking kami.users.manage from the last User holding it, on the field, saving nothing', function (): void {
    $ada = signInWith();
    Filament::setCurrentPanel('admin');

    Livewire::test(EditUser::class, ['record' => $ada->getKey()])
        ->fillForm(['name' => 'Ada renamed', 'kami_roles' => ['editor']])
        ->call('save')
        ->assertHasFormErrors(['kami_roles'])
        // Its colon would read as a rule's parameters in `assertHasFormErrors()`.
        ->assertSee(__('kami::admin.roles.lockout'));

    expect($ada->fresh()?->name)->toBe('Ada')
        ->and($ada->fresh()?->kamiRoles())->toBe(['administrator'])
        ->and(auditEvents())->toBe(['kami.users.lockout-refused']);
});

it('deletes a User, their Roles with them', function (): void {
    $grace = userWith('Grace', 'editor');

    usersPage(ListUsers::class)
        ->callAction(TestAction::make('delete')->table($grace))
        ->assertNotified();

    expect(User::query()->whereKey($grace->getKey())->exists())->toBeFalse()
        ->and(DB::table('kami_role_user')->where('user_id', $grace->getKey())->exists())->toBeFalse()
        ->and(auditEvents())->toBe(['kami.users.deleted']);
});

it('refuses deleting the last User holding kami.users.manage, saying why', function (): void {
    $ada = signInWith();
    Filament::setCurrentPanel('admin');

    Livewire::test(EditUser::class, ['record' => $ada->getKey()])
        ->callAction('delete')
        ->assertNotified(__('kami::admin.roles.lockout_delete'));

    expect($ada->fresh())->not->toBeNull()
        ->and(auditEvents())->toBe(['kami.users.lockout-refused']);
});

it('renders the Users screen in English and French with no raw translation key', function (string $locale): void {
    config(['app.locale' => $locale]);
    $grace = userWith('Grace', 'editor');
    signInWith();

    foreach (['/admin/users', '/admin/users/create', "/admin/users/{$grace->getKey()}/edit"] as $url) {
        expect((string) $this->get($url)->assertOk()->getContent())->not->toContain('users.', $url)->not->toContain('kami-roles.', $url);
    }
})->with(['en', 'fr']);
