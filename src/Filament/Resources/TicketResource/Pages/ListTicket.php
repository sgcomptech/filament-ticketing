<?php

namespace SGCompTech\FilamentTicketing\Filament\Resources\TicketResource\Pages;

use Filament\Resources\Pages\ListRecords;
use SGCompTech\FilamentTicketing\Filament\Resources\TicketResource;
use Filament\Pages\Actions\CreateAction;

class ListTicket extends ListRecords
{
    protected static string $resource = TicketResource::class;

    protected function getActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}