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
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\MultiSelectFilter;
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
		$userModel = config('filament-ticketing.user-model');
		$user = auth()->user();
		$userSchema = [];
		if ($userModel) {
			$userSchema = [
				Placeholder::make('Name')->content($user->name),
				Placeholder::make('Email')->content($user->email),
			];
		} else {
			$userSchema = [
				TextInput::make('name')->required()->maxLength(255)->disabledOn('edit'),
				TextInput::make('email')->email()->required()->maxLength(255)->disabledOn('edit'),
			];
		}
		return $form
			->schema([
				Card::make([
					...$userSchema,
					TextInput::make('title')->required()->maxLength(255)->columnSpan(2)->disabledOn('edit'),
					Textarea::make('content')->required()->columnSpan(2)->disabledOn('edit'),
					Select::make('status')->options(config('filament-ticketing.statuses'))
						->required()
						->disabled(fn ($record) => ($user->cannot('manageAllTickets', Ticket::class) &&
							($user->cannot('manageAssignedTickets', Ticket::class) || $record?->assigned_to_id != $user->id)))
						->hiddenOn('create'),
					Select::make('priority')->options(config('filament-ticketing.priorities'))
						->disabledOn('edit')
						->required(),
				])->columns(2),
			]);
	}

	public static function table(Table $table): Table
	{
		$user = auth()->user();
		/** @var mixed $user */
		$canManageAllTickets = $user->can('manageAllTickets', Ticket::class);
		$canManageAssignedTickets = $user->can('manageAssignedTickets', Ticket::class);
		return $table
			->columns([
				TextColumn::make(config('filament-ticketing.user-model') ? 'user.name' : 'name')->sortable()->searchable(),
				TextColumn::make('title')->searchable(),
				TextColumn::make('status')
					->formatStateUsing(fn ($record) => config("filament-ticketing.statuses.$record->status")),
				TextColumn::make('priority')
					->formatStateUsing(fn ($record) => config("filament-ticketing.priorities.$record->priority"))
					->color(fn ($record) => $record->priorityColor()),
				TextColumn::make('assigned_to.name'),
			])
			->filters([
				//
			])
			->actions([
				ViewAction::make(),
				EditAction::make()->label('Change Status'),
			// ])
			// ->bulkActions([
				// DeleteBulkAction::make(),
			]);
	}

	public static function getRelations(): array
	{
		return [];
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
