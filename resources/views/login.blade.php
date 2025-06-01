<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - VideoCallStudy</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-100 via-white to-blue-200 flex items-center justify-center">

    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl p-8 border border-blue-200">
        <div class="text-center mb-6">
            <h1 class="text-3xl font-bold text-blue-600">Welcome Back</h1>
            <p class="text-sm text-gray-500">Silakan login untuk melanjutkan</p>
        </div>

        <form action="{{ route('loginsubmit') }}" method="POST" class="space-y-5" onsubmit="return validateForm()">
            @csrf  

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                        <i class="fas fa-envelope"></i>
                    </span>
                    <input type="email" name="email" id="email" required
                        class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-400 focus:outline-none"
                        placeholder="you@example.com">
                </div>
                <p id="emailError" class="text-red-500 text-sm mt-1 hidden">Email harus diisi.</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                        <i class="fas fa-lock"></i>
                    </span>
                    <input type="password" name="password" id="password" required
                        class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-400 focus:outline-none"
                        placeholder="********">
                </div>
                <p id="passwordError" class="text-red-500 text-sm mt-1 hidden">Password minimal 8 karakter.</p>
            </div>

            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-md transition duration-200 shadow-md">
                Login
            </button>

            <p class="text-center text-sm text-gray-500 mt-2">
                Belum punya akun?
                <a href="{{ route('register') }}" class="text-blue-600 hover:underline">Daftar di sini</a>
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

    <script>
        function validateForm() {
            let email = document.getElementById('email').value;
            let password = document.getElementById('password').value;
            let isValid = true;

            if (!email) {
                document.getElementById('emailError').classList.remove('hidden');
                isValid = false;
            } else {
                document.getElementById('emailError').classList.add('hidden');
            }

            if (password.length < 8) {
                document.getElementById('passwordError').classList.remove('hidden');
                isValid = false;
            } else {
                document.getElementById('passwordError').classList.add('hidden');
            }

            return isValid;
        }
    </script>
</body>
</html>
