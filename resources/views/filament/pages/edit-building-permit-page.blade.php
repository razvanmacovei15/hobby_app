@php
    use App\Filament\Pages\BuildingPermitPage;
@endphp

<x-filament::page>
    <div class="rounded-2xl shadow-sm bg-white dark:bg-gray-900 p-6">
        <form wire:submit.prevent="save">
            <div class="mb-6">
                <div class="mb-6 flex items-start justify-between gap-4">
                    <div>
                        <p class="mt-1 text-sm text-gray-500">
                            {{ $this->record ? 'Edit the building permit details.' : 'Create a new building permit for this workspace.' }}
                        </p>
                    </div>
                </div>

                {{ $this->form }}
            </div>

            <div class="flex items-center gap-3">
                <x-filament::button type="submit" icon="heroicon-o-check">
                    {{ $this->record ? 'Update Permit' : 'Create Permit' }}
                </x-filament::button>

                <x-filament::button tag="a" :href="BuildingPermitPage::getUrl()" color="gray" outlined>
                    Cancel
                </x-filament::button>
            </div>
        </form>
    </div>
</x-filament::page>
