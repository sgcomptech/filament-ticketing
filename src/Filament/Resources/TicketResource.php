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
		$userSchema = [];
		if ($userModel) {
			$user = auth()->user();
			$userSchema = [
				Placeholder::make('Name')->content($user->name),
				Placeholder::make('Email')->content($user->email),
			];
		} else {
			$userSchema = [
				TextInput::make('name')->required()->maxLength(255),
				TextInput::make('email')->email()->required()->maxLength(255),
			];
		}
		return $form
			->schema([
				Card::make([
					...$userSchema,
					TextInput::make('title')->required()->maxLength(255)->columnSpan(2),
					Textarea::make('content')->required()->columnSpan(2),
					Select::make('priority')->options(config('filament-ticketing.priorities'))
						->required(),
				])->columns(2),
			]);
	}

	public static function table(Table $table): Table
	{
		return $table
			->columns([
				TextColumn::make('name')->sortable()->searchable(),
				TextColumn::make('title')->searchable(),
				SelectColumn::make('status')->options(config('filament-ticketing.statuses')),
				SelectColumn::make('priority')->options(config('filament-ticketing.priorities')),
				TextColumn::make('assigned_to.name'),
			])
			->filters([
				//
			])
			->actions([
				EditAction::make(),
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
