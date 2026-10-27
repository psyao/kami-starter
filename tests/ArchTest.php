<?php

declare(strict_types=1);

// A Filament major then touches these folders only (Kami's ADR 0023). The User model is the one exception:
// Filament admits no User into a panel outside the local environment unless it implements FilamentUser.
arch('Filament stays in app/Filament and app/Providers/Filament')
    ->expect('Filament')
    ->toOnlyBeUsedIn(['App\Filament', 'App\Providers\Filament', 'App\Models\User']);
