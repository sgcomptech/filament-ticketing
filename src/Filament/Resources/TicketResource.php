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

    protected static function getNavigationGroup(): ?string
    {
        return config('filament-ticketing.navigation.group');
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

        return $form
            ->schema([
                Card::make([
                    Placeholder::make('User Name')
                        ->content(fn ($record) => $record?->user->name)
                        ->hiddenOn('create'),
                    Placeholder::make('User Email')
                        ->content(fn ($record) => $record?->user->email)
                        ->hiddenOn('create'),
                    TextInput::make('title')->required()->maxLength(255)->columnSpan(2)->disabledOn('edit'),
                    Textarea::make('content')->required()->columnSpan(2)->disabledOn('edit'),
                    Select::make('status')->options(config('filament-ticketing.statuses'))
                        ->required()
                        ->disabled(fn ($record) => (
                            $cannotManageAllTickets &&
                            ($cannotManageAssignedTickets || $record?->assigned_to_id != $user->id)
                        ))
                        ->hiddenOn('create'),
                    Select::make('priority')->options(config('filament-ticketing.priorities'))
                        ->disabledOn('edit')
                        ->required(),
                    Select::make('assigned_to_id')
                        ->label('Assign Ticket To')
                        ->hint('Key in 3 or more characters to begin search')
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

        return $table
            ->columns([
                TextColumn::make('identifier')->searchable(),
                TextColumn::make('user.name')->sortable()->searchable(),
                TextColumn::make('title')->searchable()->words(8),
                TextColumn::make('status')
                    ->formatStateUsing(fn ($record) => config('filament-ticketing.statuses')[$record->status]),
                TextColumn::make('priority')
                    ->formatStateUsing(fn ($record) => config("filament-ticketing.priorities.$record->priority"))
                    ->color(fn ($record) => $record->priorityColor()),
                TextColumn::make('assigned_to.name')->visible($canManageAllTickets || $canManageAssignedTickets),
            ])
            ->filters([
                Filter::make('filters')
                    ->form([
                        Select::make('status')->options(config('filament-ticketing.statuses')),
                        Select::make('priority')->options(config('filament-ticketing.priorities')),
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
