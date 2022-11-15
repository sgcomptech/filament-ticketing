<?php

namespace SGCompTech\FilamentTicketing\Filament\Resources\TicketResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use SGCompTech\FilamentTicketing\Filament\Resources\TicketResource;

class CreateTicket extends CreateRecord
{
    protected static string $resource = TicketResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
		if (config('filament-ticketing.user-model')) {
            $user = auth()->user();
            $data['user_id'] = $user->id;
		} else {
            $data['user_id'] = null;
		}
        $data['status'] = 0; // first state
        return $data;
    }
}