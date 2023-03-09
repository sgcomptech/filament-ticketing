<?php

namespace Sgcomptech\FilamentTicketing\Filament\Resources\TicketResource\Pages;

use Filament\Pages\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Sgcomptech\FilamentTicketing\Events\NewAssignment;
use Sgcomptech\FilamentTicketing\Events\NewStatus;

class EditTicket extends EditRecord
{
    public $prev_assigned_to_id;

    public $prev_status;

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
        $interacted = $this->record?->ticketable;

        return __('Ticket') . ($interacted ? ' [' . $interacted?->{$interacted?->model_name()} . ']' : '');
    }

    protected function afterFill()
    {
        $this->prev_assigned_to_id = $this->record->assigned_to_id;
        $this->prev_status = $this->record->status;
    }

    protected function afterSave()
    {
        if ($this->record->assigned_to_id != $this->prev_assigned_to_id) {
            NewAssignment::dispatch($this->record);
        }

        if ($this->record->status != $this->prev_status) {
            NewStatus::dispatch($this->record);
        }
    }
}
