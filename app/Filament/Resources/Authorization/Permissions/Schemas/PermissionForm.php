<?php

namespace App\Filament\Resources\Authorization\Permissions\Schemas;

use App\Enums\PermissionCategory;
use Filament\Facades\Filament;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class PermissionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Select::make('category')
                    ->label('Category')
                    ->required()
                    ->options(PermissionCategory::options())
                    ->searchable()
                    ->helperText('Select the category this permission belongs to')
                    ->columnSpan(1),

                TextInput::make('name')
                    ->label('Permission Name')
                    ->required()
                    ->maxLength(255)
                    ->unique(
                        table: 'permissions',
                        column: 'name',
                        ignoreRecord: true,
                        modifyRuleUsing: function ($rule) {
                            $tenant = Filament::getTenant();
                            return $rule->where('workspace_id', $tenant?->id);
                        }
                    )
                    ->helperText('Use lowercase with dashes (e.g., create-work-report)')
                    ->columnSpan(1),

                TextInput::make('guard_name')
                    ->label('Guard Name')
                    ->default('web')
                    ->required()
                    ->maxLength(255)
                    ->helperText('The guard name for this permission (usually "web")')
                    ->columnSpan(1)
                    ->hidden(true),

                Textarea::make('description')
                    ->label('Description')
                    ->maxLength(500)
                    ->helperText('Optional description of what this permission allows')
                    ->columnSpanFull()
                    ->rows(3),
            ]);
    }
}
