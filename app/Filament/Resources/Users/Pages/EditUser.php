<?php

declare(strict_types=1);

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    #[\Override]
    protected static string $resource = UserResource::class;

    /** Filament saves the Roles before the User: a User that then fails to save keeps its Roles unchanged. */
    #[\Override]
    protected ?bool $hasDatabaseTransactions = true;

    protected function getHeaderActions(): array
    {
        return [UserResource::deleteAction()];
    }
}
