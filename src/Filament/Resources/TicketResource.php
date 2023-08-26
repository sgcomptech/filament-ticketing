<?php

namespace Sgcomptech\FilamentTicketing\Filament\Resources;

use Filament\Forms\Components\Card;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Sgcomptech\FilamentTicketing\Filament\Resources\TicketResource\RelationManagers\CommentsRelationManager;
use Sgcomptech\FilamentTicketing\Models\Ticket;

class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';

    protected static function getNavigationLabel(): string
    {
        return __('Ticket');
    }

    protected static function getNavigationGroup(): ?string
    {
        return __(config('filament-ticketing.navigation.group'));
    }

    protected static function getNavigationSort(): ?int
    {
        return config('filament-ticketing.navigation.sort');
    }

    public static function form(Form $form): Form
    {
        $user = auth()->user();
        if (config('filament-ticketing.use_authorization')) {
            $cannotManageAllTickets = $user->cannot('manageAllTickets', Ticket::class);
            $cannotManageAssignedTickets = $user->cannot('manageAssignedTickets', Ticket::class);
            $cannotAssignTickets = $user->cannot('assignTickets', Ticket::class);
        } else {
            $cannotManageAllTickets = false;
            $cannotManageAssignedTickets = false;
            $cannotAssignTickets = false;
        }

        $statuses = array_map(fn ($e) => __($e), config('filament-ticketing.statuses'));
        $priorities = array_map(fn ($e) => __($e), config('filament-ticketing.priorities'));

        return $form
            ->schema([
                Card::make([
                    Placeholder::make('User Name')
                        ->label(__('User Name'))
                        ->content(fn ($record) => $record?->user->name)
                        ->hiddenOn('create'),
                    Placeholder::make('User Email')
                        ->label(__('User Email'))
                        ->content(fn ($record) => $record?->user->email)
                        ->hiddenOn('create'),
                    TextInput::make('title')
                        ->translateLabel()
                        ->required()
                        ->maxLength(255)
                        ->columnSpan(2)
                        ->disabledOn('edit'),
                    Textarea::make('content')
                        ->translateLabel()
                        ->required()
                        ->columnSpan(2)
                        ->disabledOn('edit'),
                    Select::make('status')
                        ->translateLabel()
                        ->options($statuses)
                        ->required()
                        ->disabled(fn ($record) => (
                            $cannotManageAllTickets &&
                            ($cannotManageAssignedTickets || $record?->assigned_to_id != $user->id)
                        ))
                        ->hiddenOn('create'),
                    Select::make('priority')
                        ->translateLabel()
                        ->options($priorities)
                        ->disabledOn('edit')
                        ->required(),
                    Select::make('assigned_to_id')
                        ->label(__('Assign Ticket To'))
                        ->hint(__('Key in 3 or more characters to begin search'))
                        ->searchable()
                        ->getSearchResultsUsing(function ($search) {
                            if (strlen($search) < 3) {
                                return [];
                            }

                            return config('filament-ticketing.user-model')::where('name', 'like', "%{$search}%")
                                ->limit(50)
                                ->get()
                                ->filter(fn ($user) => $user->can('manageAssignedTickets', Ticket::class))
                                ->pluck('name', 'id');
                        })
                        ->getOptionLabelUsing(fn ($value): ?string => config('filament-ticketing.user-model')::find($value)?->name)
                        ->disabled($cannotAssignTickets)
                        ->hiddenOn('create'),
                ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        $user = auth()->user();
        if (config('filament-ticketing.use_authorization')) {
            $canManageAllTickets = $user->can('manageAllTickets', Ticket::class);
            $canManageAssignedTickets = $user->can('manageAssignedTickets', Ticket::class);
        } else {
            $canManageAllTickets = true;
            $canManageAssignedTickets = true;
        }

        $statuses = array_map(fn ($e) => __($e), config('filament-ticketing.statuses'));
        $priorities = array_map(fn ($e) => __($e), config('filament-ticketing.priorities'));

        return $table
            ->columns([
                TextColumn::make('identifier')
                    ->translateLabel()
                    ->searchable(),
                TextColumn::make('ticketable.name')
                    ->translateLabel(),
                TextColumn::make('user.name')
                    ->translateLabel()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('title')
                    ->translateLabel()
                    ->searchable()
                    ->words(8),
                TextColumn::make('status')
                    ->translateLabel()
                    ->formatStateUsing(fn ($record) => $statuses[$record->status] ?? '-'),
                TextColumn::make('priority')
                    ->translateLabel()
                    ->formatStateUsing(fn ($record) => $priorities[$record->priority] ?? '-')
                    ->color(fn ($record) => $record->priorityColor()),
                TextColumn::make('assigned_to.name')
                    ->translateLabel()
                    ->visible($canManageAllTickets || $canManageAssignedTickets),
            ])
            ->filters([
                Filter::make('filters')
                    ->form([
                        Select::make('status')
                            ->translateLabel()
                            ->options($statuses),
                        Select::make('priority')
                            ->translateLabel()
                            ->options($priorities),
                    ])
                    ->query(
                        fn (Builder $query, array $data) => $query
                        ->when($data['status'], fn ($query, $status) => $query->where('status', $status))
                        ->when($data['priority'], fn ($query, $priority) => $query->where('priority', $priority))
                    ),
            ])
            ->actions([
                // ViewAction::make(),
                EditAction::make()
                    ->visible($canManageAllTickets || $canManageAssignedTickets),
            ])
            ->bulkActions([
                // DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            CommentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => TicketResource\Pages\ListTicket::route('/'),
            'create' => TicketResource\Pages\CreateTicket::route('/create'),
            'edit' => TicketResource\Pages\EditTicket::route('/{record}/edit'),
        ];
    }
}
