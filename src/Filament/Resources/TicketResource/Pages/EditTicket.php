<?php

namespace Sgcomptech\FilamentTicketing\Filament\Resources\TicketResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Filament\Pages\Actions\DeleteAction;
use Sgcomptech\FilamentTicketing\Events\NewAssignment;
use Sgcomptech\FilamentTicketing\Models\Ticket;

class EditTicket extends EditRecord
{
    public $prev_assigned_to_id;

    public static function getResource(): string
    {
        return config('filament-ticketing.ticket-resource');
    }

    protected function getActions(): array
    {
        return [DeleteAction::make()];
    }

    protected function getTitle(): string
    {
        $associated = $this->record?->ticketable;
        return 'Ticket [' . $associated?->{$associated?->model_name()} . ']';
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