<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Samen Shoppen</title>
    <link rel="stylesheet" href="{{ asset('style.css') }}">

    <!-- JS inladen via Vite voor Pop-up bij Ritten -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="flex flex-col min-h-screen">

    <header class="w-full">
        {{ $header }}
    </header>

    <main class="p-4 pb-32 flex-grow">
        {{ $slot }}
    </main>

    <x-navbar />

</body>

</html>