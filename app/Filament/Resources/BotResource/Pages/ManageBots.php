<?php

namespace App\Filament\Resources\BotResource\Pages;

use App\Filament\Resources\BotResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageBots extends ManageRecords
{
    protected static string $resource = BotResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
