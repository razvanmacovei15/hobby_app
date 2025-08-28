<?php

namespace App\Filament\Resources\Authorization\Roles\Schemas;

use App\Enums\PermissionCategory;
use App\Models\Permission\Permission;
use Filament\Facades\Filament;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class RoleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Role Name')
                    ->required()
                    ->maxLength(255)
                    ->unique(
                        table: 'roles',
                        column: 'name',
                        ignoreRecord: true,
                        modifyRuleUsing: function ($rule) {
                            $tenant = Filament::getTenant();
                            return $rule->where('workspace_id', $tenant?->id);
                        }
                    )
                    ->helperText('Use lowercase with dashes (e.g., project-manager)'),

                TextInput::make('display_name')
                    ->label('Display Name')
                    ->maxLength(255)
                    ->helperText('Human readable name (e.g., Project Manager)'),

                ...static::getDynamicPermissionSections(),
            ]);
    }

    protected static function getDynamicPermissionSections(): array
    {
        $tenant = Filament::getTenant();

        if (!$tenant) {
            return [];
        }

        // Get all distinct categories that have permissions in this workspace
        $categories = Permission::where('workspace_id', $tenant->id)
            ->whereNotNull('category')
            ->distinct()
            ->pluck('category')
            ->filter()
            ->sort();

        $sections = [];

        foreach ($categories as $categoryEnum) {
            // $categoryEnum is already a PermissionCategory enum due to the model cast
            if (!$categoryEnum instanceof PermissionCategory) {
                continue;
            }

            $sections[] = Section::make($categoryEnum->label())
                ->description($categoryEnum->description())
                ->schema([
                    CheckboxList::make("permission_ids_{$categoryEnum->value}")
                        ->label('')
                        ->options(function () use ($tenant, $categoryEnum) {
                            return Permission::query()
                                ->where('workspace_id', $tenant->id)
                                ->where('category', $categoryEnum)
                                ->pluck('name', 'id')
                                ->toArray();
                        })
                        ->descriptions(function () use ($tenant, $categoryEnum) {
                            return Permission::query()
                                ->where('workspace_id', $tenant->id)
                                ->where('category', $categoryEnum)
                                ->pluck('description', 'id')
                                ->filter()
                                ->toArray();
                        })
                        ->afterStateHydrated(function (CheckboxList $component, $state, $record) use ($categoryEnum, $tenant) {
                            if ($record) {
                                $categoryPermissionIds = $record->permissions()
                                    ->where('workspace_id', $tenant->id)
                                    ->where('category', $categoryEnum)
                                    ->pluck('permissions.id')
                                    ->toArray();
                                $component->state($categoryPermissionIds);
                            }
                        })
                        ->dehydrated(true)
                        ->columns(3)
                        ->gridDirection('row'),
                ])
                ->columnSpanFull()
                ->collapsible();
        }

        return $sections;
    }
}
