<?php

namespace Sgcomptech\FilamentTicketing\Filament\Resources\TicketResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Sgcomptech\FilamentTicketing\Filament\Resources\TicketResource;
use Filament\Pages\Actions\DeleteAction;

class EditTicket extends EditRecord
{
    protected static string $resource = TicketResource::class;

    protected function getActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}