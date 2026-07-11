@extends('layouts.user')

@section('title', 'Terms of Service')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl overflow-hidden border" style="border-color: var(--td-border);">
            <div class="p-8 sm:p-12 prose dark:prose-invert max-w-none" style="color: var(--td-text);">
                {!! $terms !!}
            </div>
        </div>
    </div>
</div>
@endsection
