<?php

namespace App\Filament\Pages;

use App\Enums\PermitType;
use App\Enums\PermitStatus;
use App\Models\BuildingPermit;
use Filament\Facades\Filament;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EditBuildingPermitPage extends Page
{
    protected static string|null|\BackedEnum $navigationIcon = null;
    protected static ?string $navigationLabel = null;
    protected static ?string $title = 'Edit building permit';
    protected string $view = 'filament.pages.edit-building-permit-page';
    protected static bool $shouldRegisterNavigation = false;

    public ?array $data = [];
    public ?BuildingPermit $record = null;

    public function mount(): void
    {
        $workspace = Filament::getTenant();

        if ($workspace?->buildingPermit) {
            // Editing existing
            $this->record = $workspace->buildingPermit;
            $this->form->fill($this->record->attributesToArray());
            static::$title = 'Edit building permit';
        } else {
            // Creating new
            $this->form->fill([]);
            static::$title = 'Create building permit';
        }
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Permit Information')
                    ->schema([
                        TextInput::make('permit_number')
                            ->label('Permit Number')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->formatStateUsing(fn ($record) => $record?->permit_number),

                        Select::make('permit_type')
                            ->label('Permit Type')
                            ->options(PermitType::class)
                            ->required(),

                        Select::make('status')
                            ->label('Status')
                            ->options(PermitStatus::class)
                            ->default(PermitStatus::PENDING)
                            ->required(),
                    ])
                    ->columns(3),
            ])
            ->record($this->record)
            ->statePath('data');
    }

    public function save(): void
    {
        $workspace = Filament::getTenant();
        $state = $this->form->getState();

        if ($this->record) {
            // Update existing permit
            $this->record->update($state);
        } else {
            // Create new permit
            $state['workspace_id'] = $workspace->id;
            $this->record = BuildingPermit::create($state);
        }

        $this->record = $this->record->fresh();

        Notification::make()
            ->success()
            ->title('Building permit saved')
            ->send();

        // Back to read-only page
        $this->redirect(BuildingPermitPage::getUrl());
    }
}
