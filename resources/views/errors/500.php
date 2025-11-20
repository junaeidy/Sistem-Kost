<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Server Error</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="text-center">
            <h1 class="text-9xl font-bold text-gray-800">500</h1>
            <p class="text-2xl font-semibold text-gray-600 mt-4">Server Error</p>
            <p class="text-gray-500 mt-2">Maaf, terjadi kesalahan pada server.</p>
            <a href="<?= url('/') ?>" class="mt-6 inline-block px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Kembali ke Beranda
            </a>
        </div>
    </div>
</body>
</html>
