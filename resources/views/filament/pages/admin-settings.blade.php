<x-filament-panels::page>
    <form wire:submit="save" class="space-y-6">
        {{ $this->form }}

        <!-- Added inline styles because custom Tailwind classes aren't compiled in the admin panel -->
        <div style="display: flex; justify-content: flex-start; gap: 1rem; margin-top: 3rem; padding-top: 1rem;">
            <x-filament::button type="submit" color="primary">
                Save Changes
            </x-filament::button>
        </div>
    </form>

    <x-filament-actions::modals />
</x-filament-panels::page>
