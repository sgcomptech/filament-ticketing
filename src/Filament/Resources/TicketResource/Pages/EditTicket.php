<?php

namespace Sgcomptech\FilamentTicketing\Filament\Resources\TicketResource\Pages;

use Filament\Pages\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Sgcomptech\FilamentTicketing\Events\NewAssignment;

class EditTicket extends EditRecord
{
    public $prev_assigned_to_id;

    public function getAllowance($permission)
    {
        $globalPermission = match($permission){
            'viewTicket' => 'viewAllTickets',
            'deleteTicket' => 'deleteAllTickets',
        };
        $user = auth()->user();
        if (!config('filament-ticketing.use_authorization')
            || $this->record?->user_id === $user?->id
            || $user?->can($globalPermission)
        ) {
            return true;
        }
        return false;
    }

    public static function getResource(): string
    {
        return config('filament-ticketing.ticket-resource');
    }

    protected function getActions(): array
    {
        if(!$this->getAllowance('deleteTicket')){
            return [];
        }
        return [DeleteAction::make()];
    }

    protected function getTitle(): string
    {
        $interacted = $this->record?->ticketable;

        return __('Ticket') . ($interacted ? ' [' . $interacted?->{$interacted?->model_name()} . ']' : '');
    }

    protected function beforeFill(){
        if(!$this->getAllowance('viewTicket')){
            abort(401);
        }
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
