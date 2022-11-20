<?php

namespace Sgcomptech\FilamentTicketing\Filament\Resources\TicketResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Resources\Form;
use Filament\Forms\Components\Textarea;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Livewire\Component as LivewireComponent;
use Sgcomptech\FilamentTicketing\Models\Comment;
use Sgcomptech\FilamentTicketing\Models\Ticket;

class CommentsRelationManager extends RelationManager
{
    protected static string $relationship = 'comments';
    protected static ?string $recordTitleAttribute = 'user.name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // TextArea::make('content')->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        $user = auth()->user();

        return $table
            ->columns([
                Stack::make([
                    Split::make([
                        TextColumn::make('user.name')
                            ->weight('bold')
                            ->color(fn (LivewireComponent $livewire) =>
                                $livewire->ownerRecord->user_id == $user->id ? 'primary' : 'success')
                            ->grow(false),
                        TextColumn::make('created_at')->dateTime()->color('secondary'),
                    ]),
                    TextColumn::make('content')->wrap(),
                ]),
            ])
            ->headerActions([
                Action::make('addComment')
                    ->label('Add Comment')
                    ->form([
                        Textarea::make('content')->required(),
                    ])
                    ->action(function (array $data, LivewireComponent $livewire) use ($user): void {
                        $ticket = $livewire->ownerRecord;
                        abort_unless(
                            config('filament-ticketing.use_authorization') == false ||
                            $ticket->user_id == $user->id || $ticket->assigned_to_id == $user->id ||
                            $user->can('manageAllTickets', Ticket::class), 403);
                        Comment::create([
                            'content' => $data['content'],
                            'user_id' => $user->id,
                            'ticket_id' => $livewire->ownerRecord->id,
                        ]);
                    })
            ])
            ->defaultSort('id', 'desc');
    }
}
