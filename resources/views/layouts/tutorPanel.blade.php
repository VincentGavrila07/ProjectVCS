<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tutor</title>
    <!-- SweetAlert2, Tailwind & FontAwesome -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
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
</head>
<body class="bg-gray-100">

    <div class="flex h-screen">
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
                    <a href="{{ route('tutor') }}" class="block py-2 px-4 mt-2 hover:bg-gray-700 rounded {{ Route::is('tutor') ? 'bg-yellow-500' : '' }}">
                        Dashboard
                    </a>
                </li>
                <li>
                    <a href="{{ route('profile.edit.tutor') }}" class="block py-2 px-4 mt-2 hover:bg-gray-700 rounded {{ Route::is('profile.edit.tutor') ? 'bg-yellow-500' : '' }}">
                        Edit Profile
                    </a>
                </li>
                <li>
                    <a href="{{ route('chat.index') }}" class="block py-2 px-4 mt-2 hover:bg-gray-700 rounded 
                        {{ request()->routeIs('chat.index') || request()->routeIs('chat.room') ? 'bg-yellow-500' : '' }}">
                        Chat
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

     // Fungsi untuk mengecek notifikasi baru
     function checkNotification() {
        fetch('/check-notification')
            .then(response => response.json())
            .then(data => {
                if (data.has_notification) {
                    // Tampilkan popup notifikasi
                    let timer = 10;
                    Swal.fire({
                        title: 'Permintaan Sewa',
                        html: `Anda sedang disewa oleh pelajar. Silakan konfirmasi dalam <b>${timer}</b> detik.`,
                        timer: 10000, // 10 detik
                        timerProgressBar: true,
                        showCancelButton: true,
                        confirmButtonText: 'Terima',
                        cancelButtonText: 'Tolak',
                        didOpen: () => {
                            const timerElement = Swal.getHtmlContainer().querySelector('b');
                            const timerInterval = setInterval(() => {
                                timer--;
                                timerElement.textContent = timer;
                            }, 1000);

                            // Hentikan interval saat popup ditutup
                            Swal.getPopup().addEventListener('mouseenter', () => {
                                clearInterval(timerInterval);
                            });
                            Swal.getPopup().addEventListener('mouseleave', () => {
                                timerInterval = setInterval(() => {
                                    timer--;
                                    timerElement.textContent = timer;
                                }, 1000);
                            });
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Jika tutor menerima
                            fetch('/confirm-request', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({ transaction_id: data.transaction_id }) // Pastikan transaction_id dikirim
                            }).then(response => response.json())
                              .then(data => {
                                  if (data.success) {
                                      Swal.fire({
                                          icon: 'success',
                                          title: 'Berhasil',
                                          text: 'Anda telah menerima permintaan sewa.',
                                      }).then(() => {
                                          window.location.reload(); // Refresh halaman
                                      });
                                  } else {
                                      Swal.fire({
                                          icon: 'error',
                                          title: 'Oops...',
                                          text: data.message || 'Terjadi kesalahan saat memproses permintaan.',
                                      });
                                  }
                              });
                        } else if (result.dismiss === Swal.DismissReason.cancel) {
                            // Jika tutor menolak
                            fetch('/reject-request', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({ transaction_id: data.transaction_id })
                            }).then(response => response.json())
                              .then(data => {
                                  if (data.success) {
                                      Swal.fire({
                                          icon: 'success',
                                          title: 'Berhasil',
                                          text: 'Anda telah menolak permintaan sewa.',
                                      }).then(() => {
                                          window.location.reload(); // Refresh halaman
                                      });
                                  } else {
                                      Swal.fire({
                                          icon: 'error',
                                          title: 'Oops...',
                                          text: data.message || 'Terjadi kesalahan saat memproses permintaan.',
                                      });
                                  }
                              });
                        }
                    });
                }
            })
            .catch(error => {
                console.error('Error saat mengecek notifikasi:', error);
            });
    }

    // Cek notifikasi setiap 5 detik
    setInterval(checkNotification, 5000);
    </script>

</body>
</html>