<?php

namespace Sgcomptech\FilamentTicketing\Filament\Resources\TicketResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Sgcomptech\FilamentTicketing\Filament\Resources\TicketResource;

class CreateTicket extends CreateRecord
{
    public $rec, $recid;
    protected $queryString = ['rec', 'recid'];
    protected static string $resource = TicketResource::class;
    protected static bool $canCreateAnother = false;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
		if (config('filament-ticketing.user-model')) {
            $user = auth()->user();
            $data['user_id'] = $user->id;
		} else {
            $data['user_id'] = null;
		}

        if ($this->rec && $this->recid) {
            $data['ticketable_type'] = $this->rec;
            $data['ticketable_id'] = $this->recid;
        }

        $data['status'] = 0; // first state
        return $data;
    }

    protected function getSubheading(): ?string
    {
        if ($this->rec) {
            $recInstance = $this->rec::findOrFail($this->recid);
            return $recInstance->{$recInstance->model_name()};
        } else {
            return null;
        }
    }
}