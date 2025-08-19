@php
    $panel = filament()->getCurrentPanel();
    $current = filament()->getTenant();
    $tenants = auth()->user()?->getTenants($panel) ?? collect();
@endphp

<x-filament::dropdown placement="bottom-end" :teleport="true">
    <x-slot name="trigger">
        <x-filament::button icon="heroicon-m-building-office-2" color="gray" size="sm">
            {{ $current?->name ?? 'Select workspace' }}
        </x-filament::button>
    </x-slot>

    <x-filament::dropdown.list>
        @forelse ($tenants as $tenant)
            <x-filament::dropdown.list.item
                tag="a"
                :href="filament()->getUrl($tenant)"
                icon="heroicon-m-building-office-2"
                :badge="$current && $tenant->is($current) ? 'Current' : null"
            >
                {{ $tenant->name }}
            </x-filament::dropdown.list.item>
        @empty
            <x-filament::dropdown.list.item disabled>
                No workspaces available
            </x-filament::dropdown.list.item>
        @endforelse
    </x-filament::dropdown.list>
</x-filament::dropdown>
