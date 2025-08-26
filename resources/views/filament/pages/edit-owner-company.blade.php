<x-filament::page>
    <form wire:submit="save">
        {{ $this->form }}

        <div class="mt-4">
            <x-filament::button type="submit" color="success" icon="heroicon-o-check-circle" >Save</x-filament::button>
            <x-filament::button tag="a" :href="App\Filament\Pages\OwnerCompany::getUrl()" icon="heroicon-o-x-circle" color="cancel">
                Cancel
            </x-filament::button>
        </div>
    </form>
</x-filament::page>
