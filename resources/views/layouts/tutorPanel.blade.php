<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tutor</title>
    <!-- SweetAlert2, Tailwind & FontAwesome -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
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
</head>
<body class="bg-gray-100">

    <div class="flex h-screen">
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
                        <img src="{{ asset('images/user.jpg') }}" 
                            alt="Profile Picture" 
                            class="w-24 h-24 rounded-full border-4 border-gray-300 shadow-md object-cover">
                    @endif
                </div>
                <div class="text-center">
                    <p class="font-semibold text-lg">{{ session('username') }}</p>
                    <p class="text-gray-400 text-sm">{{ session('email') }}</p>
                    <div class="flex items-center space-x-1">
                        @php
                            $rating = number_format(session('rating') ?? 0, 1);
                        @endphp

                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= floor($rating))
                                <i class="fas fa-star text-yellow-400 text-xl"></i>
                            @elseif ($i - $rating < 1)
                                <i class="fas fa-star-half-alt text-yellow-400 text-xl"></i>
                            @else
                                <i class="far fa-star text-gray-300 text-xl"></i>
                            @endif
                        @endfor

                        <span class="ml-2 text-gray-600 text-sm font-semibold">{{ $rating }}/5</span>
                    </div>
                </div>
            </div>
            
            <!-- Toggle Availability -->
            <div class="flex items-center justify-between mb-4">
                <span>Pencarian</span>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" id="availabilityToggle" class="sr-only peer">
                    <div class="w-11 h-6 bg-gray-200 peer-focus:ring-4 peer-focus:ring-blue-300 
                        dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 
                        peer-checked:after:translate-x-full peer-checked:after:border-white 
                        after:content-[''] after:absolute after:top-0.5 after:left-[2px] 
                        after:bg-white after:border-gray-300 after:border after:rounded-full 
                        after:h-5 after:w-5 after:transition-all dark:border-gray-600 
                        peer-checked:bg-blue-600"></div>
                </label>
            </div>
            <!-- Menu -->
            <ul>
                <li>
                    <a href="{{ route('tutor') }}" class="block py-2 px-4 mt-2 hover:bg-gray-700 rounded {{ Route::is('tutor') ? 'bg-yellow-500' : '' }}">
                        Dashboard
                    </a>
                </li>
                <li class="relative">
                    <a href="{{ route('profile.edit.tutor') }}" 
                        class="block py-2 px-4 mt-2 hover:bg-gray-700 rounded 
                        {{ Route::is('profile.edit.tutor') ? 'bg-yellow-500' : '' }}">
                        Edit Profile
                        @if(!session('price') || !session('subjectClass') || !session('image'))
                            <span class="absolute top-2 right-3 w-3 h-3 bg-red-500 rounded-full animate-pulse"></span>
                        @endif
                    </a>
                </li>
                <li>
                    <a href="{{ route('forum.threads.index') }}"
                    class="block py-2 px-4 mt-2 hover:bg-gray-700 rounded 
                            {{ Route::is('forum.*') ? 'bg-yellow-500 text-white' : '' }}">
                        Thread
                    </a>
                </li>

                <li class="relative">
                    <a href="{{ route('chatting.index') }}" class="block py-2 px-4 mt-2 hover:bg-gray-700 rounded
                        {{ request()->routeIs('chatting.index') || request()->routeIs('chatting.room') ? 'bg-yellow-500' : '' }}">
                        Chatting
                    </a>
                    <span class="absolute top-2 right-3 w-3 h-3 bg-red-500 rounded-full animate-pulse" style="display: none;"></span>
                </li>
                <li>
                    <a href="{{ route('tutor.transaksiList') }}" class="block py-2 px-4 mt-2 hover:bg-gray-700 rounded {{ Route::is('tutor.transaksiList') ? 'bg-yellow-500' : '' }}">
                        History 
                    </a>
                </li>
                <li>
                    <a href="{{ route('tutor.wallet') }}" class="block py-2 px-4 mt-2 hover:bg-gray-700 rounded 
                        {{ request()->routeIs('tutor.wallet') ? 'bg-yellow-500' : '' }}">
                        Withdraw
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

    window.onpageshow = function(event) {
        if (event.persisted) {
            location.reload(); // Refresh halaman jika user kembali dengan tombol "Back"
        }
    };

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
                                  // Redirect ke halaman video call
                                  window.location.href = data.video_call_url;
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


// isAvailable

document.addEventListener("DOMContentLoaded", function() {
    const toggleSwitch = document.getElementById("availabilityToggle");

    // Set nilai awal berdasarkan session
    fetch('/get-tutor-status')
        .then(response => response.json())
        .then(data => {
            toggleSwitch.checked = data.isAvailable;
        });

    toggleSwitch.addEventListener("change", function() {
        fetch('/tutor/toggle-availability', {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: "success",
                    title: "Status Updated",
                    text: "Availability changed successfully!",
                    timer: 1500
                });
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: data.message
                });
            }
        })
        .catch(error => {
            console.error("Error updating availability:", error);
        });
    });
});


// menangani untuk function afk

document.addEventListener("DOMContentLoaded", function () {
    let timeout;
    const toggleSwitch = document.getElementById("availabilityToggle");
    
    // Fungsi untuk mengupdate status ke false jika user AFK
    function setAFK() {
        fetch('/tutor/set-afk', {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: "warning",
                    title: "Anda AFK",
                    text: "Anda telah AFK selama 30 menit. Status Anda diubah menjadi tidak tersedia.",
                    timer: 5000
                });
                toggleSwitch.checked = false; // Update tampilan toggle
            }
        })
        .catch(error => console.error("Error setting AFK:", error));
    }

    // Reset timer setiap ada aktivitas user
    function resetTimer() {
        clearTimeout(timeout);
        timeout = setTimeout(setAFK, 30 * 60 * 1000); // 30 menit
        // timeout = setTimeout(setAFK, 10 * 1000); // 10 Detik
    }

    // Event listener untuk aktivitas user
    document.addEventListener("mousemove", resetTimer);
    document.addEventListener("keydown", resetTimer);
    document.addEventListener("click", resetTimer);
    document.addEventListener("scroll", resetTimer);

    // Mulai hitungan waktu AFK saat pertama kali masuk
    resetTimer();   
});

document.addEventListener('DOMContentLoaded', function () {
    const checkUnreadMessages = () => {
        fetch('/unread-messages-count')
            .then(response => response.json())
            .then(data => {
                const unreadCount = data.unreadMessagesCount;
                console.log('Unread messages:', unreadCount);

                const chattingLink = document.querySelector('a[href="{{ route('chatting.index') }}"]');
                const chattingLi = chattingLink?.parentElement;

                if (!chattingLi) return;

                const redDot = chattingLi.querySelector('span');

                if (unreadCount > 0) {
                    if (redDot) {
                        redDot.style.display = 'block';
                    }
                } else {
                    if (redDot) {
                        redDot.style.display = 'none';
                    }
                }
            })
            .catch(err => console.error('Error checking unread messages:', err));
    };

    setInterval(checkUnreadMessages, 5000);
    checkUnreadMessages();
});


</script>

</body>
</html>