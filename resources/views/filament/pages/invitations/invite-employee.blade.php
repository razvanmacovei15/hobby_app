<x-filament-panels::page>
    <form wire:submit="submit">
        {{ $this->form }}
        
        <div class="mt-6">
            <x-filament::button type="submit">
                Invite Employee
            </x-filament::button>
        </div>
    </form>
    
    <x-filament-actions::modals />
</x-filament-panels::page>
