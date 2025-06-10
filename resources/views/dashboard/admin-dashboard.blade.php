<x-app-layout>
    <x-slot name="header">
        {{-- Page header, if you want --}}
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Mount your Livewire AdminDashboard component here --}}
            <livewire:admin.admin-dashboard />
        </div>
    </div>
</x-app-layout>