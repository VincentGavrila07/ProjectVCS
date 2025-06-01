<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-100 via-white to-blue-200">

    <div class="w-full max-w-md bg-white p-8 rounded-2xl shadow-xl border border-blue-200">
        <h2 class="text-3xl font-bold text-center text-blue-600 mb-6">Daftar Akun</h2>

        <form action="{{ route('registersubmit') }}" method="POST" class="space-y-5">
            @csrf  

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                <div class="relative">
                    <i class="fas fa-user absolute left-3 top-2.5 text-gray-400"></i>
                    <input type="text" name="username" required
                        class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-400 focus:outline-none"
                        placeholder="Masukkan nama pengguna">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <div class="relative">
                    <i class="fas fa-envelope absolute left-3 top-2.5 text-gray-400"></i>
                    <input type="email" name="email" required
                        class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-400 focus:outline-none"
                        placeholder="you@example.com">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <div class="relative">
                    <i class="fas fa-lock absolute left-3 top-2.5 text-gray-400"></i>
                    <input type="password" name="password" required
                        class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-400 focus:outline-none"
                        placeholder="********">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kamu Sebagai:</label>
                <div class="flex items-center gap-4 mt-1">
                    <label class="inline-flex items-center gap-2">
                        <input type="radio" name="role" value="1" required>
                        <span>Tutor</span>
                    </label>
                    <label class="inline-flex items-center gap-2">
                        <input type="radio" name="role" value="2" required>
                        <span>Pelajar</span>
                    </label>
                </div>
            </div>

            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-md transition duration-200 shadow-md">
                Register
            </button>

            <p class="text-center text-sm text-gray-500 mt-2">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Login di sini</a>
            </p>
        </form>

        @if ($errors->any())
            <div class="text-red-500 text-sm mt-4 text-center">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

</body>
</html>
