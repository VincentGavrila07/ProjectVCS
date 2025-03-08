<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pelajar</title>
    <!-- SweetAlert2, Tailwind & FontAwesome -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<style>
    #sidebar {
        height: 100vh; /* Tinggi sidebar sesuai viewport */
        width: 16rem; /* Lebar sidebar */
        background-color: #1f2937; /* Warna latar sidebar */
        position: fixed; /* Sidebar tetap di tempatnya */
        top: 0;
        left: 0;
    }
</style>
<body class="bg-gray-100">

    <div class="flex h-screen">
        <!-- Sidebar -->
       <!-- Sidebar -->
        <!-- Sidebar -->
        <div id="sidebar" class="w-64 bg-gray-800 text-white p-6 fixed inset-y-0 left-0 transform -translate-x-full transition-transform duration-300 ease-in-out md:translate-x-0 md:sticky md:top-0 md:h-screen">
            <!-- Profile Section -->
            <div class="flex justify-center mb-6">
                <img src="{{ asset('images/LogoVcs.png') }}" 
                    alt="Logo Picture" 
                    class="w-32 h-32 md:w-40 md:h-40 lg:w-48 lg:h-48 transition-all duration-300">
            </div>

            <!-- Profile Section -->
            <div class="flex items-center mb-6">
                <div class="w-16 h-16 rounded-full bg-gray-500 flex items-center justify-center mr-4">
                    @if(session('image'))
                        <img src="{{ asset('storage/' . session('image')) }}" alt="Profile Picture" class="w-16 h-16 rounded-full border-2 border-blue-400 shadow-md">
                    @else
                        <img src="{{ asset('images/user.jpg') }}" alt="Profile Picture" class="w-16 h-16 rounded-full border-2 border-gray-300 shadow-md">
                    @endif
                </div>
                <div class="text-sm">
                    <p class="font-semibold">{{ session('username') }}</p>
                    <p class="text-gray-400">{{ session('email') }}</p>
                </div>
            </div>

            <!-- Menu -->
            <ul>
                <li>
                    <a href="{{ route('pelajar') }}" class="block py-2 px-4 mt-2 hover:bg-gray-700 rounded  {{ Route::is('pelajar') ? 'bg-yellow-500' : '' }}">
                        Dashboard
                    </a>
                </li>
                <li>
                    <a href="{{ route('profile.edit.pelajar') }}" class="block py-2 px-4 mt-2 hover:bg-gray-700 rounded  {{ Route::is('profile.edit.pelajar') ? 'bg-yellow-500' : '' }}">
                        Edit Profile
                    </a>
                </li>
                <li>
                    <a href="{{ route('findTutor') }}" class="block py-2 px-4 mt-2 hover:bg-gray-700 rounded {{ Route::is('findTutor') ? 'bg-yellow-500' : '' }}">
                        Cari Tutor
                    </a>
                </li>
                <li>
                    <a href="{{ route('chat.index') }}" class="block py-2 px-4 mt-2 hover:bg-gray-700 rounded 
                        {{ request()->routeIs('chat.index') || request()->routeIs('chat.room') ? 'bg-yellow-500' : '' }}">
                        Chat
                    </a>
                </li>
                <li>
                    <a href="{{ route('wallet.index') }}" class="block py-2 px-4 mt-2 hover:bg-gray-700 rounded 
                        {{ Route::is('wallet.index') ? 'bg-yellow-500' : '' }}">
                        Wallet
                    </a>
                </li>
                <li>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full text-left block py-2 px-4 mt-2 hover:bg-red-600 rounded">
                            Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>

        <!-- Tombol Toggle Sidebar -->
        <button id="toggleSidebar" class="fixed top-4 left-4 text-white p-4 bg-gray-800 rounded-md z-50 md:hidden transition-all duration-300">
            <i id="toggleIcon" class="fas fa-bars"></i>
        </button>



        <!-- Content Area -->
        <div id="content" class="flex-1 p-8 transition-all duration-300 mt-12 min-h-screen md:ml-64">
            @yield('content') <!-- Tempat untuk konten menu -->
        </div>
    </div>

    <script>
    const sidebar = document.getElementById('sidebar');
    const toggleButton = document.getElementById('toggleSidebar');
    const toggleIcon = document.getElementById('toggleIcon');

    toggleButton.addEventListener('click', function () {
        if (sidebar.classList.contains('-translate-x-full')) {
            sidebar.classList.remove('-translate-x-full');
            toggleButton.classList.add('left-64'); // Pindahkan tombol ke dalam sidebar
            toggleIcon.classList.remove('fa-bars');
            toggleIcon.classList.add('fa-arrow-left');
        } else {
            sidebar.classList.add('-translate-x-full');
            toggleButton.classList.remove('left-64'); // Kembalikan tombol ke posisi semula
            toggleIcon.classList.remove('fa-arrow-left');
            toggleIcon.classList.add('fa-bars');
        }
    });

    </script>

</body>
</html>
