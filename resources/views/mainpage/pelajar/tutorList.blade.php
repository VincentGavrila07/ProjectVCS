@forelse($tutors as $tutor)
    <div class="bg-white shadow-lg rounded-xl p-4 flex flex-col items-center text-center border border-gray-200">
        @php
            $tutorImage = $tutor->image ? asset('storage/' . $tutor->image) : asset('images/user.jpg');
        @endphp

        <img src="{{ $tutorImage }}" 
            alt="Tutor Image" 
            class="w-24 h-24 object-cover rounded-full border-4 border-blue-400 shadow-md">

        <h3 class="text-lg font-semibold mt-3">{{ $tutor->username }}</h3>
        <span class="text-sm text-blue-500 font-medium">Tutor ID: {{ $tutor->TeacherId }}</span>
        
        <p class="text-sm text-gray-500 mt-1 font-bold">{{ $tutor->subject_name ?? '-' }}</p>

        <div class="flex justify-center items-center mt-2 text-gray-500 text-sm">
            <span class="font-bold">
                Bergabung {{ $tutor->experience }}
                <span class="ml-3 flex items-center">
                    @php
                        $rating = number_format($tutor->ratingtutor ?? 0, 1); // Rating dengan 1 angka di belakang koma
                        $fullStars = floor($rating); // Bintang penuh
                        $halfStar = ($rating - $fullStars) >= 0.5 ? 1 : 0; // Bintang setengah
                        $emptyStars = 5 - $fullStars - $halfStar; // Bintang kosong
                    @endphp

                    <!-- Bintang penuh -->
                    @for ($i = 0; $i < $fullStars; $i++)
                        <span class="text-yellow-500">★</span>
                    @endfor

                    <!-- Bintang setengah -->
                    @if ($halfStar)
                        <span class="text-yellow-500">⯨</span> <!-- Menggunakan karakter setengah bintang -->
                    @endif

                    <!-- Bintang kosong -->
                    @for ($i = 0; $i < $emptyStars; $i++)
                        <span class="text-gray-300">★</span>
                    @endfor

                    <!-- Menampilkan rating angka di samping bintang -->
                    <span class="ml-2 text-gray-700">({{ $rating }})</span>

                    <!-- Jika rating kosong, tampilkan "Belum ada rating" -->
                    @if ($rating == 0)
                        <span class="text-gray-500 ml-2">Belum ada rating</span>
                    @endif
                </span>
        </div>
        <p class="text-sm text-gray-700 font-medium mt-2">
            {{ $tutor->price ? 'Rp ' . number_format($tutor->price, 0, ',', '.') . '/jam' : 'Gratis' }}
        </p>

        <a href="{{ route('chatting.create', ['tutor_id' => $tutor->id]) }}" 
            class="mt-3 px-4 py-2 bg-blue-500 text-white text-sm font-semibold rounded-lg shadow-md hover:bg-blue-600 transition duration-200 cursor-pointer">
            💬 Chat Sekarang
        </a>

        <a href="#" 
            onclick="sewaTutor({{ $tutor->id }})" 
            class="mt-3 px-4 py-2 bg-green-500 text-white text-sm font-semibold rounded-lg shadow-md hover:bg-green-600 transition duration-200 cursor-pointer">
            🛒 Sewa Sekarang
        </a>
    </div>
@empty
    <p class="text-center col-span-4 text-gray-500">Tidak ada tutor yang ditemukan.</p>
@endforelse
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Swal loading saat halaman dimuat
    document.addEventListener('DOMContentLoaded', function () {
        Swal.fire({
            title: 'Memuat tutor...',
            html: 'Mohon tunggu sebentar.',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Tutup Swal setelah konten benar-benar dirender (opsional: timer fallback)
        setTimeout(() => {
            Swal.close();
        }, 3500); // atau kamu bisa ganti ini jadi observer kalau lebih dinamis
    });
</script>
