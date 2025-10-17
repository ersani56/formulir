<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Formulir Unggah Dokumen Pegawai</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Filament Styles -->
    @filamentStyles
</head>
<body class="font-sans antialiased bg-gray-100">
    {{ $slot }}

    <!-- Filament Scripts -->
    @filamentScripts

    <!-- Livewire Scripts -->
    @livewireScripts
</body>
</html>
