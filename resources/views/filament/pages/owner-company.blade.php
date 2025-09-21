@php
    use App\Filament\Pages\EditOwnerCompany;
@endphp

<x-filament::page>
    {{-- Wrap everything in a centered container --}}
        @if ($this->record)
            {{-- VIEW MODE: centered card with heading + infolist --}}
            <div class="rounded-2xl  shadow-sm bg-white dark:bg-gray-900 p-6">
                <div class="mb-6 flex items-start justify-between gap-4">
                    <div>
{{--                        <h2 class="text-xl font-semibold">Owner company</h2>--}}
                        <p class="mt-1 text-sm text-gray-500">
                            Company details for this workspace.
                        </p>
                    </div>

                    {{-- optional top-right edit (keep this OR header action, not both) --}}
                    @if(auth()->user()?->canInWorkspace(OwnerCompanyPermission::EDIT->value))
                        <x-filament::button tag="a" :href="EditOwnerCompany::getUrl()" icon="heroicon-o-pencil" size="sm" color="edit" label="Edit Company Details">
                            Edit Company Details
                        </x-filament::button>
                    @endif
                </div>

                {{-- Infolist content --}}
                <div class="mt-2">
                    {{ $this->companyInfolist }}
                </div>
            </div>
        @else
            {{-- EMPTY STATE: fully centered vertically --}}
            <div class="min-h-[60vh] flex items-center justify-center">
                <div class="w-full max-w-2xl text-center">
                    <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full border bg-white dark:bg-gray-900 shadow-sm">
                        <x-filament::icon icon="heroicon-o-building-office" class="h-7 w-7 text-gray-500" />
                    </div>

                    <h2 class="text-2xl font-semibold">No company is set for this workspace</h2>
                    <p class="mt-2 text-sm text-gray-500">
                        Create your owner company to manage legal details, representatives, and addresses in one place.
                    </p>

                    <div class="mt-6 flex items-center justify-center gap-3">
                        @if(auth()->user()?->canInWorkspace('owner-company.edit'))
                            <x-filament::button tag="a" :href="EditOwnerCompany::getUrl()" icon="heroicon-o-plus">
                                Create company
                            </x-filament::button>
                        @endif
                    </div>
                </div>
            </div>
        @endif
</x-filament::page>
