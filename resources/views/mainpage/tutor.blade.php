<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Tutor</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="bg-white shadow-lg rounded-lg p-8">
        <h1 class="text-2xl font-bold text-gray-800">Selamat Datang, {{ session('username') }}</h1>
        <p class="text-gray-600 mt-2">Teacher ID: <span class="font-semibold">{{session('TeacherId') }}</span></p>

        <a href="{{ route('logout') }}" class="mt-4 inline-block bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">Logout</a>
    </div>
</body>
</html>
