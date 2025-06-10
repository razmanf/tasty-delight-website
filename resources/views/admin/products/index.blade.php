@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold text-gray-800">📦 Products</h1>
        <a href="{{ route('admin.products.create') }}"
           class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded shadow">
            ➕ Add Product
        </a>
    </div>

    @if(session('success'))
    <div class="mb-4 p-4 rounded bg-green-100 text-green-800 shadow">
        {{ session('success') }}
    </div>
    @endif

    {{-- Livewire component handles everything from here --}}
    @livewire('admin.product-table')
</div>
@endsection
