<?php

namespace Sgcomptech\FilamentTicketing\Filament\Resources\TicketResource\RelationManagers;

use Filament\Forms\Components\Textarea;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component as LivewireComponent;
use Sgcomptech\FilamentTicketing\Events\NewComment;
use Sgcomptech\FilamentTicketing\Events\NewResponse;
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
                            ->color(fn (LivewireComponent $livewire, Model $record) => $livewire->ownerRecord->user_id == $record->user_id ? 'primary' : 'success')
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
                            $ticket->user_id == $user->id ||
                            $ticket->assigned_to_id == $user->id ||
                            $user->can('manageAllTickets', Ticket::class),
                            403
                        );
                        $comment = Comment::create([
                            'content' => $data['content'],
                            'user_id' => $user->id,
                            'ticket_id' => $livewire->ownerRecord->id,
                        ]);
                        if ($livewire->ownerRecord->user_id == $user->id) {
                            NewComment::dispatch($comment);
                        } else {
                            NewResponse::dispatch($comment);
                        }
                    }),
            ])
            ->defaultSort('id', 'desc');
    }
}
