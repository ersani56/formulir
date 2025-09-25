<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir App</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="text-center">
            <h1 class="text-4xl font-bold text-gray-800 mb-4">Selamat Datang</h1>
            <p class="text-lg text-gray-600 mb-8">Aplikasi Formulir Pendaftaran</p>
            <a href="{{ route('formulir.create') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-lg transition duration-200">
                Isi Formulir Pendaftaran
            </a>
            <div class="mt-4">
                <a href="/admin" class="text-blue-600 hover:text-blue-800 text-sm">
                    Login Admin Panel
                </a>
            </div>
        </div>
    </div>
</body>
</html>
