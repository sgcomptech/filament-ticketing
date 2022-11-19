<?php

namespace Sgcomptech\FilamentTicketing\Filament\Resources\TicketResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Sgcomptech\FilamentTicketing\Filament\Resources\TicketResource;
use Illuminate\Support\Str;

class CreateTicket extends CreateRecord
{
    public $rec, $recid;
    protected $queryString = ['rec', 'recid'];
    protected static string $resource = TicketResource::class;
    protected static bool $canCreateAnother = false;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if ($this->rec && $this->recid) {
            $data['ticketable_type'] = $this->rec;
            $data['ticketable_id'] = $this->recid;
        }

        $data['user_id'] = auth()->id();
        $data['identifier'] = strtoupper(Str::random(8));
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