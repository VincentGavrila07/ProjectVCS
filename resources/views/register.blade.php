<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex items-center justify-center min-h-screen bg-gray-100">

    <div class="w-full max-w-md bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-2xl font-semibold text-center mb-4">Daftar Akun</h2>
        <form action="{{ route('registersubmit') }}" method="POST" class="space-y-4">
            @csrf  

            <div>
                <label class="block font-medium">Username:</label>
                <input type="text" name="username" required 
                    class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <div>
                <label class="block font-medium">Email:</label>
                <input type="email" name="email" required 
                    class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <div>
                <label class="block font-medium">Password:</label>
                <input type="password" name="password" required 
                    class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <div class="flex items-center space-x-4">
                <div>
                    <label class="inline-block font-medium">Kamu Sebagai:</label><br>
                    <input type="radio" name="role" value="1" required> Tutor
                    <input type="radio" name="role" value="2" required> Pelajar
                </div>
            </div>

            <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-md hover:bg-blue-600 transition">Register</button>

            <p class="text-center mt-2 text-gray-600">Sudah punya akun? 
                <a href="{{ route('login') }}" class="text-blue-500 hover:underline">Login di sini</a>
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

</body>
</html>
