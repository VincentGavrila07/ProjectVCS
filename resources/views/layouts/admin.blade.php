<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <!-- SweetAlert2, Tailwind & FontAwesome -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<style>
    #sidebar {
        height: 100vh; /* Sidebar setinggi viewport */
        width: 16rem; /* Lebar tetap */
        background-color: #1f2937; /* Warna sidebar */
        position: fixed; /* Tetap di tempat */
        top: 0;
        left: 0;
        overflow-y: auto; /* Scrollable jika konten melebihi */
        scrollbar-width: none; /* Hilangkan scrollbar di Firefox */
    }

    /* Hilangkan scrollbar di Chrome, Edge, Safari */
    #sidebar::-webkit-scrollbar {
        display: none;
    }

</style>
<body class="bg-gray-100">
    @if (session('role') != 3)
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Access Denied',
                text: 'Anda tidak memiliki akses ke halaman ini!',
            }).then(() => {
                window.location.href = '/';
            });
        </script>
        @php exit; @endphp
    @endif
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <div id="sidebar" class="w-64 bg-gray-800 text-white p-6 fixed inset-y-0 left-0 transform -translate-x-full transition-transform duration-300 ease-in-out md:translate-x-0 md:sticky md:top-0 md:h-screen">
            <!-- Logo Section -->
            <div class="flex justify-center mb-6">
                <img src="{{ asset('images/LogoVcs.png') }}" 
                    alt="Logo Picture" 
                    class="w-16 h-16 md:w-24 md:h-24 lg:w-32 lg:h-32 transition-all duration-300">
            </div>

            <!-- Profile Section -->
            <div class="flex flex-col items-center mb-6">
                <div class="w-24 h-24 rounded-full bg-gray-500 flex items-center justify-center mb-4">
                    @if(session('image'))
                        <img src="{{ asset('storage/' . session('image')) }}" 
                            alt="Profile Picture" 
                            class="w-24 h-24 rounded-full border-4 border-blue-400 shadow-md object-cover">
                    @else
                        <img src="{{ asset('images/kucink.jpeg') }}" 
                            alt="Profile Picture" 
                            class="w-24 h-24 rounded-full border-4 border-blue-600 shadow-md object-cover">
                    @endif
                </div>
                <div class="text-center">
                    <p class="font-semibold text-lg">{{ session('username') }}</p>
                    <p class="text-gray-400 text-sm">{{ session('email') }}</p>
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
                    <a href="{{ route('forum.threads.index') }}"
                    class="block py-2 px-4 mt-2 hover:bg-gray-700 rounded 
                            {{ Route::is('forum.*') ? 'bg-yellow-500 text-white' : '' }}">
                        Thread
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
                    <a href="{{ route('transaksiList') }}" class="block py-2 px-4 mt-2 hover:bg-gray-700 rounded {{ Request::is('admin/transaksiList') ? 'bg-yellow-500' : '' }}">
                        Daftar Transaksi
                    </a>
                </li>
                <li>
                    <a href="{{ route('subjectList') }}" class="block py-2 px-4 mt-2 hover:bg-gray-700 rounded {{ Request::is('admin/subjectList') ? 'bg-yellow-500' : '' }}">
                        Daftar Subject
                    </a>
                </li>
                <li>
                    <a href="{{ route('withdrawList') }}" class="block py-2 px-4 mt-2 hover:bg-gray-700 rounded {{ Request::is('admin/withdrawList') ? 'bg-yellow-500' : '' }}">
                        Daftar Withdraw
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
            <i id="toggleIcon" class="fas fa-bars"></i> <!-- Ikon hamburger -->
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
                // Buka sidebar
                sidebar.classList.remove('-translate-x-full');
                toggleButton.classList.add('left-64'); // Pindahkan tombol ke dalam sidebar
                toggleIcon.classList.remove('fa-bars');
                toggleIcon.classList.add('fa-arrow-left'); // Ubah ikon ke panah kiri
            } else {
                // Tutup sidebar
                sidebar.classList.add('-translate-x-full');
                toggleButton.classList.remove('left-64'); // Kembalikan tombol ke posisi semula
                toggleIcon.classList.remove('fa-arrow-left');
                toggleIcon.classList.add('fa-bars'); // Kembalikan ikon ke hamburger
            }
        });
    </script>

</body>
</html>