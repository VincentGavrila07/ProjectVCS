@extends('layouts.pelajarPanel')

@section('content')
    <h2 class="text-2xl font-semibold mb-4">Find Tutor</h2>

    <!-- Search Bar & Filter -->
    <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
        <!-- Form Search -->
        <form method="GET" action="{{ route('findTutor') }}" class="mb-6" id="filterisasi">
            <div class="flex flex-wrap gap-4 items-center">
                <!-- Search -->
                <div class="flex w-full sm:w-2/3">
                    <input type="text" name="search" placeholder="Cari tutor berdasarkan nama, ID, atau mata pelajaran..."
                        class="w-full px-4 py-2 border rounded-l-lg focus:outline-none focus:ring-1 focus:ring-blue-300"
                        value="{{ request('search') }}">
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-r-lg hover:bg-blue-600 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m2.85-6.15A7.5 7.5 0 1112 4.5a7.5 7.5 0 017.5 7.5z" />
                        </svg>
                    </button>

                </div>

                <!-- Filter Range Harga -->
                <div class="flex items-center">
                    <label class="mr-2 text-sm font-semibold text-gray-700">Harga:</label>
                    <input type="number" name="min_price" placeholder="Min" class="px-3 py-2 border rounded-lg w-20"
                        value="{{ request('min_price') }}">
                    <span class="mx-2">-</span>
                    <input type="number" name="max_price" placeholder="Max" class="px-3 py-2 border rounded-lg w-20"
                        value="{{ request('max_price') }}">
                </div>

                <!-- Filter Pengalaman (bulan) -->
                <div class="flex items-center">
                    <label class="mr-2 text-sm font-semibold text-gray-700">Pengalaman (bulan):</label>
                    <select name="min_experience" class="px-3 py-2 border rounded-lg w-28" >
                        <option value="">Semua</option>
                        <option value="1" {{ request('min_experience') == '1' ? 'selected' : '' }}>1 bulan</option>
                        <option value="3" {{ request('min_experience') == '3' ? 'selected' : '' }}>3 bulan</option>
                        <option value="6" {{ request('min_experience') == '6' ? 'selected' : '' }}>6 bulan</option>
                        <option value="12" {{ request('min_experience') == '12' ? 'selected' : '' }}>1 tahun</option>
                        <option value="24" {{ request('min_experience') == '24' ? 'selected' : '' }}>2 tahun</option>
                        <option value="36" {{ request('min_experience') == '36' ? 'selected' : '' }}>3 tahun</option>
                        <option value="48" {{ request('min_experience') == '48' ? 'selected' : '' }}>4 tahun</option>
                        <option value="60" {{ request('min_experience') == '60' ? 'selected' : '' }}>5 tahun</option>
                    </select>
                </div>


                <!-- Filter Subject -->
                <div>
                    <select name="subject" class="px-3 py-2 border rounded-lg text-gray-700">
                        <option value="">Pilih Mata Pelajaran</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ request('subject') == $subject->id ? 'selected' : '' }}>
                                {{ $subject->subjectName }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Tombol Submit -->
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white font-semibold rounded-lg hover:bg-blue-600">
                    Terapkan Filter
                </button>
                <a href="{{ route('findTutor') }}" class="ml-2 px-4 py-2 bg-blue-500 text-white font-semibold rounded-lg hover:bg-blue-600">
                        Reset Filter
                </a>
                <!-- Floating Button -->
                <a id="filterNowBtn" href="#filterisasi" 
                        class="hidden fixed bottom-4 right-4 bg-blue-500 text-white px-4 py-2 rounded-full shadow-lg hover:bg-blue-600 transition duration-300">
                    🔍 Filter Now
                </a>

            </div>
        </form>

    </div>

    <!-- Grid Card Tutor -->
    <div id="tutor-list" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @include('mainpage.pelajar.tutorList', ['tutors' => $tutors])
    </div>

<script>
// ajax untuk real time tutor tanpa harus refresh
document.addEventListener("DOMContentLoaded", function () {
    function loadTutors() {
        fetch("{{ route('tutors.get') }}")
            .then(response => response.text())
            .then(html => {
                document.getElementById("tutor-list").innerHTML = html;
            })
            .catch(error => console.error("Error loading tutors:", error));
    }

    // Refresh daftar tutor setiap 5 detik
    setInterval(loadTutors, 5000);
});


document.addEventListener("DOMContentLoaded", function () {
    const filterSection = document.querySelector("form"); // Elemen filter
    const filterNowBtn = document.getElementById("filterNowBtn");

    function checkScroll() {
        if (!filterSection) return;

        const rect = filterSection.getBoundingClientRect();
        
        if (rect.bottom >100) { 
            filterNowBtn.classList.remove("hidden");
        } else {
            filterNowBtn.classList.add("hidden");
        }
    }

    window.addEventListener("scroll", checkScroll);

    // Ketika tombol diklik, scroll kembali ke bagian filter
    filterNowBtn.addEventListener("click", function () {
        filterSection.scrollIntoView({ behavior: "smooth" });
    });
});
function sewaTutor(tutorId) {
    // Kirim request ke backend untuk membuat transaksi
    fetch('/sewa-tutor', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ tutor_id: tutorId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Tampilkan popup timer 20 detik
            let timer = 20;
            Swal.fire({
                title: 'Menunggu Konfirmasi Tutor',
                html: `Tutor memiliki waktu <b>${timer}</b> detik untuk merespons.`,
                timer: 20000, // 20 detik
                timerProgressBar: true,
                showConfirmButton: false, // Menghilangkan tombol "OK"
                didOpen: () => {
                    const timerElement = Swal.getHtmlContainer().querySelector('b');
                    const timerInterval = setInterval(() => {
                        timer--;
                        timerElement.textContent = timer;
                        if (timer <= 0) {
                            clearInterval(timerInterval);
                        }
                    }, 1000);
                }
            }).then((result) => {
                if (result.dismiss === Swal.DismissReason.timer) {
                    // Jika waktu habis, tampilkan pesan
                    Swal.fire({
                        icon: 'error',
                        title: 'Waktu Habis',
                        text: 'Tutor tidak merespons dalam waktu yang ditentukan.',
                        showConfirmButton: true, // Tampilkan tombol "OK" pada pesan ini
                    });
                }
            });

            // Cek status transaksi secara berkala (polling)
            const checkConfirmation = setInterval(() => {
                fetch('/check-transaction-status', { // Endpoint untuk memeriksa status transaksi
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ transaction_id: data.transaction_id }) // Kirim transaction_id
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Response from /check-transaction-status:', data); // Debugging
                    if (data.status === 'confirmed') {
                        clearInterval(checkConfirmation); // Hentikan polling

                        // Redirect ke halaman video call
                        window.location.href = data.video_call_url;
                    } else if (data.status === 'rejected') {
                        clearInterval(checkConfirmation); // Hentikan polling

                        // Tampilkan pesan penolakan
                        Swal.fire({
                            icon: 'error',
                            title: 'Ditolak',
                            text: 'Tutor menolak permintaan sewa Anda.',
                            showConfirmButton: true,
                        });
                    }
                })
                .catch(error => {
                    console.error('Error saat memeriksa status transaksi:', error);
                });
            }, 3000); // Cek setiap 3 detik
        } else {
            // Jika gagal membuat transaksi, tampilkan pesan error
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: data.message || 'Terjadi kesalahan saat memproses permintaan.',
                showConfirmButton: true, // Tampilkan tombol "OK" pada pesan ini
            });
        }
    })
    .catch(error => {
        // Jika terjadi error saat mengirim request, tampilkan pesan error
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Terjadi kesalahan saat menghubungi server.',
            showConfirmButton: true, // Tampilkan tombol "OK" pada pesan ini
        });
    });
}
</script>

@endsection