<?php

declare(strict_types=1);

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Psyao\Kami\Users\HasKamiRoles;
use Psyao\Kami\Users\KamiUser;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements FilamentUser, KamiUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory;

    use HasKamiRoles;
    use Notifiable;

    /**
     * Without this, Filament lets no User into a panel outside the local environment.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        // Only staff get in: a User holding a Kami Role. What each may do there is a Permission check (Kami's Users and roles guide).
        return $this->kamiRoles() !== [];
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
