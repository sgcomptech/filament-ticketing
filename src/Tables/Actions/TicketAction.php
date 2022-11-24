<?php

namespace Sgcomptech\FilamentTicketing\Tables\Actions;

use Filament\Tables\Actions\Action;

class TicketAction extends Action
{
    protected function setUp(): void
    {
        $this->label('Ticket');

        $this->icon('heroicon-o-ticket');

        $this->url(fn ($record) => route('filament.resources.tickets.index', [
            'recid' => $record->id,
            'rec' => $record->model_class() ?? null,
        ]));
    }
}
