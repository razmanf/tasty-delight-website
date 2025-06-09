@extends('layouts.admin')

@section('content')
    <h1>Admin Settings</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.settings.update') }}" method="POST">
        @csrf
        <div>
            <label for="site_name">Site Name</label>
            <input type="text" id="site_name" name="site_name" value="{{ old('site_name', 'Tasty Delight') }}" required>
        </div>

        <div>
            <label for="email">Contact Email</label>
            <input type="email" id="email" name="email" value="{{ old('email', 'admin@example.com') }}" required>
        </div>

        <!-- Add more settings fields as needed -->

        <button type="submit">Save Settings</button>
    </form>
@endsection
