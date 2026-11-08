<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

pest()->extend(TestCase::class)->in('Feature');

/** MFA set up for `$user`: a User who may manage Users is otherwise sent to set it up before any admin page, and `kami:doctor` warns. */
function withMfa(User $user): User
{
    DB::table('kami_user_mfa')->insert(['user_id' => $user->getKey(), 'secret' => Crypt::encryptString('JBSWY3DPEHPK3PXP')]);

    return $user;
}
