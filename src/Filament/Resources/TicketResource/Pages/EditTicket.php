<?php

namespace SGCompTech\FilamentTicketing\Filament\Resources\TicketResource\Pages;

use Filament\Resources\Pages\EditRecord;
use SGCompTech\FilamentTicketing\Filament\Resources\TicketResource;
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