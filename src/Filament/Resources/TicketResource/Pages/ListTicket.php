<?php

namespace SGCompTech\FilamentTicketing\Filament\Resources\TicketResource\Pages;

use Filament\Resources\Pages\ListRecords;
use SGCompTech\FilamentTicketing\Filament\Resources\TicketResource;
use Filament\Pages\Actions\CreateAction;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ListTicket extends ListRecords
{
    public $rec, $recid;
    protected ?Model $related_ticket;

    protected static string $resource = TicketResource::class;

    function __construct() {
        $this->queryString = ['rec', 'recid', ...parent::$queryString];
    }
    
    public function mount(): void
    {
        parent::mount();
        $this->related_ticket = empty($this->rec) ?
            null :
            $this->rec::findOrFail($this->recid);
    }

    protected function getActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    protected function getHeader(): ?View
    {
        return null;
        return $this->rec
            ? view('filament-ticketing::partials.ticket-list-header',
                ['name' => $this->related_ticket->{$this->related_ticket->model_name()}])
            : null;
    }

    protected function getTableQuery(): Builder
    {
        return $this->rec
            ? parent::getTableQuery()
                ->where('ticketable_type', $this->rec)
                ->where('ticketable_id', $this->recid)
            : parent::getTableQuery();
    }
}
