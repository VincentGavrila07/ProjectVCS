<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex items-center justify-center min-h-screen bg-gray-100">

    <div class="w-full max-w-md bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-2xl font-semibold text-center mb-4">Login</h2>
        <form action="{{ route('loginsubmit') }}" method="POST" class="space-y-4" onsubmit="return validateForm()">
            @csrf  

            <div>
                <label class="block font-medium">Email:</label>
                <input type="email" name="email" id="email" required 
                    class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400">
                <p id="emailError" class="text-red-500 text-sm hidden">Email harus diisi.</p>
            </div>

            <div>
                <label class="block font-medium">Password:</label>
                <input type="password" name="password" id="password" required 
                    class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400">
                <p id="passwordError" class="text-red-500 text-sm hidden">Password minimal 8 karakter.</p>
            </div>

            <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-md hover:bg-blue-600 transition">Login</button>
            <p class="text-center mt-2 text-gray-600">Belum punya akun? 
                <a href="{{ route('register') }}" class="text-blue-500 hover:underline">Daftar di sini</a>
            </p>
        </form>
        @if ($errors->any())
        <div class="text-red-500 text-center mt-2 text-gray-600">
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
