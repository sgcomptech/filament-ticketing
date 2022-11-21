<?php

namespace Sgcomptech\FilamentTicketing\Filament\Resources\TicketResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Sgcomptech\FilamentTicketing\Filament\Resources\TicketResource;
use Filament\Pages\Actions\DeleteAction;
use Sgcomptech\FilamentTicketing\Events\NewAssignment;
use Sgcomptech\FilamentTicketing\Models\Ticket;

class EditTicket extends EditRecord
{
    protected static string $resource = TicketResource::class;
    public $prev_assigned_to_id;

    protected function getActions(): array
    {
        return (!config('filament-ticketing.use_authorization') ||
            auth()->user()->can('deleteTickets', Ticket::class)) ? [
                DeleteAction::make(),
            ] : [];
    }

    protected function getTitle(): string
    {
        return 'Ticket';
    }

    protected function afterFill()
    {
        $this->prev_assigned_to_id = $this->record->assigned_to_id;
    }

    protected function afterSave()
    {
        if ($this->record->assigned_to_id != $this->prev_assigned_to_id) {
            NewAssignment::dispatch($this->record);
        }
    }
}