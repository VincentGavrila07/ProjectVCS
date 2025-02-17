<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <!-- swal CDN -->
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">

    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-gray-800 text-white p-6">
            <!-- Profile Section -->
            <div class="flex items-center mb-6">
                <!-- Foto Profil -->
                <div class="w-16 h-16 rounded-full bg-gray-500 flex items-center justify-center mr-4">
                    <img src="{{ asset('images/user.jpg') }}" alt="Profile Picture" class="w-16 h-16 rounded-full">
                </div>
                
                <!-- Username -->
                <div class="text-sm">
                        <p class="font-semibold">Admin</p>
                        <p class="text-gray-400">admin@gmail.com</p>
                </div>
            </div>

            <!-- Menu -->
            <ul>
                <li>
                    <a href="{{ route('admin') }}" class="block py-2 px-4 mt-2 hover:bg-gray-700 rounded {{ Route::is('admin') ? 'bg-yellow-500' : '' }}">
                        Dashboard
                    </a>
                </li>
                <li>
                    <a href="{{ route('userList') }}" class="block py-2 px-4 mt-2 hover:bg-gray-700 rounded {{ Request::is('admin/userList') ? 'bg-yellow-500' : '' }}">
                        Daftar User
                    </a>
                </li>
                <li>
                    <a href="{{ route('tutorList') }}" class="block py-2 px-4 mt-2 hover:bg-gray-700 rounded {{ Request::is('admin/tutorList') ? 'bg-yellow-500' : '' }}">
                        Daftar Tutor
                    </a>
                </li>
                <li>
                    <a href="{{ route('pelajarList') }}" class="block py-2 px-4 mt-2 hover:bg-gray-700 rounded {{ Request::is('admin/pelajarList') ? 'bg-yellow-500' : '' }}">
                        Daftar Pelajar
                    </a>
                </li>
                <li>
                    <a href="{{ route('logout') }}" class="block py-2 px-4 mt-6 hover:bg-red-600 rounded">
                        Logout
                    </a>
                </li>
            </ul>
        </div>

        <!-- Content Area -->
        <div class="flex-1 p-8">
            @yield('content') <!-- Tempat untuk konten menu -->
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
</body>
</html>
