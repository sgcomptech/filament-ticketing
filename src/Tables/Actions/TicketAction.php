<?php

namespace SGCompTech\FilamentTicketing\Tables\Actions;

use Filament\Tables\Actions\Action;
use SGCompTech\FilamentTicketing\Filament\Resources\TicketResource\Pages\ListTicket;

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
