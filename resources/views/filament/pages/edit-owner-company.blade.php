<x-filament::page>
    <form wire:submit="save">
        {{ $this->form }}

        <div class="mt-4">
            <x-filament::button type="submit">Save</x-filament::button>
            <x-filament::button tag="a" :href="App\Filament\Pages\OwnerCompany::getUrl()">
                Cancel
            </x-filament::button>
        </div>
    </form>
</x-filament::page>
