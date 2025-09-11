<?php

namespace App\Filament\Resources\WorkspaceInvitations\Tables;

use App\Models\WorkspaceInvitation;
use App\Services\IWorkspaceInvitationService;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Facades\Filament;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\App;

class WorkspaceInvitationsTable
{
    public static function configure(Table $table): Table
    {
        $workspace = Filament::getTenant();

        return $table
            ->query(WorkspaceInvitation::query()->where('workspace_id', $workspace->id)->with(['invitee', 'invitedBy', 'workspace']))
            ->columns([
                TextColumn::make('invitee.email')
                    ->label('Invited User')
                    ->searchable()
                    ->copyable(),

                TextColumn::make('invitee.first_name')
                    ->label('Name')
                    ->formatStateUsing(fn ($record) =>
                        ($record->invitee->first_name ?? 'N/A') . ' ' . ($record->invitee->last_name ?? '')
                    )
                    ->searchable(),

                TextColumn::make('invitedBy.first_name')
                    ->label('Invited By')
                    ->formatStateUsing(fn ($record) =>
                        $record->invitedBy->first_name . ' ' . $record->invitedBy->last_name
                    )
                    ->searchable(),

                BadgeColumn::make('status')
                    ->label('Status')
                    ->getStateUsing(function ($record) {
                        if ($record->isAccepted()) return 'accepted';
                        if ($record->isExpired()) return 'expired';
                        return 'pending';
                    })
                    ->colors([
                        'success' => 'accepted',
                        'danger' => 'expired',
                        'warning' => 'pending',
                    ]),

                TextColumn::make('created_at')
                    ->label('Sent At')
                    ->dateTime()
                    ->sortable(),

                TextColumn::make('expires_at')
                    ->label('Expires At')
                    ->dateTime()
                    ->sortable(),

                TextColumn::make('accepted_at')
                    ->label('Accepted At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'accepted' => 'Accepted',
                        'expired' => 'Expired',
                    ])
                    ->query(function ($query, $data) {
                        if (!$data['value']) return $query;

                        return match($data['value']) {
                            'accepted' => $query->whereNotNull('accepted_at'),
                            'expired' => $query->where('expires_at', '<', now())->whereNull('accepted_at'),
                            'pending' => $query->where('expires_at', '>', now())->whereNull('accepted_at'),
                            default => $query
                        };
                    }),
            ])
            ->recordActions([
//                ViewAction::make(),

                Action::make('resend')
                    ->label('Resend')
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->visible(fn ($record) => !$record->isAccepted())
                    ->action(function ($record) {
                        $invitationService = App::make(IWorkspaceInvitationService::class);

                        try {
                            $invitationService->resendInvitation($record->id);

                            \Filament\Notifications\Notification::make()
                                ->title('Invitation Resent')
                                ->body('The invitation has been sent again to ' . $record->invitee->email)
                                ->success()
                                ->send();
                        } catch (\Exception $e) {
                            \Filament\Notifications\Notification::make()
                                ->title('Failed to Resend')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),

                Action::make('copy_link')
                    ->label('Copy Link')
                    ->icon('heroicon-o-link')
                    ->color('gray')
                    ->visible(fn ($record) => !$record->isAccepted() && !$record->isExpired())
                    ->action(function ($record) {
                        $url = route('register.from-invitation', $record->token);

                        \Filament\Notifications\Notification::make()
                            ->title('Invitation Link')
                            ->body('Link copied: ' . $url)
                            ->success()
                            ->persistent()
                            ->send();
                    }),

                Action::make('cancel')
                    ->label('Cancel')
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->visible(fn ($record) => !$record->isAccepted())
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $invitationService = App::make(IWorkspaceInvitationService::class);

                        try {
                            $invitationService->cancelInvitation($record->id);

                            \Filament\Notifications\Notification::make()
                                ->title('Invitation Cancelled')
                                ->success()
                                ->send();
                        } catch (\Exception $e) {
                            \Filament\Notifications\Notification::make()
                                ->title('Failed to Cancel')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->poll('30s');
    }
}
