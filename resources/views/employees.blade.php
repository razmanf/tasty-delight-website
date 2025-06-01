<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Employees</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body class="bg-blue-50">
    @include('partials.navbar')
    <div class="flex justify-center h-screen">
        <div class="text-center">
            <h1 class="text-5xl font-extrabold text-indigo700">Employees</h1>
            <p class="mt-4 text-lg text-indigo-500">Managing all employee details</p>
        </div>
    </div>
</body>

</html>