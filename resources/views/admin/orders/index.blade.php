@extends('layouts.admin')

    @section('header')
        <h1 class="font-semibold text-3xl text-gray-800 leading-tight">Orders</h1>
    @endsection

    @section('content')
    <livewire:admin.order-table />
@endsection