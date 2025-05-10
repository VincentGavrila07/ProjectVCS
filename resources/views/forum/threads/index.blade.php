@extends(
    session('role') == 3 ? 'layouts.admin' : 
    (session('role') == 2 ? 'layouts.pelajarPanel' : 'layouts.tutorPanel')
)

@section('content')
<div class="py-4 px-4 max-w-5xl mx-auto">

    {{-- Sticky Header (dalam content) --}}
    <div class="sticky top-0 bg-white py-4 px-6 z-20 border-b border-gray-200 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <h2 class="text-xl font-bold text-gray-800">Thread</h2>
            
            <form action="{{ route('forum.threads.index') }}" method="GET" class="flex w-full md:w-2/3">
                <input type="text" name="search" placeholder="Cari thread..."
                    class="w-full px-4 py-2 border border-r-0 rounded-l-lg focus:outline-none focus:ring-1 focus:ring-blue-300"
                    value="{{ request('search') }}">
                <button type="submit"
                    class="px-4 py-2 bg-blue-500 text-white rounded-r-lg hover:bg-blue-600 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-4.35-4.35m2.85-6.15A7.5 7.5 0 1112 4.5a7.5 7.5 0 017.5 7.5z" />
                    </svg>
                </button>
            </form>

            <a href="{{ route('forum.threads.create') }}"
            class="bg-blue-600 text-white px-5 py-2 rounded-full shadow hover:bg-blue-700 transition whitespace-nowrap">
                + Buat Thread
            </a>
        </div>
    </div>

    @if(request('search'))
        <p class="text-sm text-gray-500 mb-4">
            Hasil pencarian untuk: <span class="font-semibold text-gray-800">"{{ request('search') }}"</span>
        </p>
    @endif

    {{-- Thread List --}}
    <div class="space-y-6">
        @forelse ($threads as $thread)
            <div class="bg-white p-6 rounded-2xl shadow border-l-4 border-blue-100 hover:shadow-md transition duration-300 relative thread-card">
                
                {{-- Badge Kategori --}}
                <span class="inline-block bg-blue-50 text-blue-600 text-sm font-medium rounded-full px-3 py-1 mb-3">
                    {{ $thread->thread_subject ?? 'General' }}
                </span>

                {{-- Judul Thread --}}
                <h3 class="text-lg font-semibold text-gray-800 mb-2">
                    <a href="{{ route('forum.threads.show', $thread->id) }}" class="hover:text-blue-600 transition">
                        {{ $thread->title }}
                    </a>
                </h3>

                {{-- Preview Isi --}}
                <p class="text-gray-600 mb-4">{{ Str::limit($thread->content, 70) }}</p>

                {{-- Footer Thread --}}
                <div class="flex justify-between items-center text-sm">
                    {{-- Profil --}}
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('storage/' . $thread->image) }}"
                            class="w-12 h-12 rounded-full border-4 border-blue-400 shadow-md object-cover"
                            alt="Avatar {{ $thread->username }}">
                        <div>
                            <p class="text-gray-400">Ditulis oleh</p>
                            <p class="font-semibold text-gray-800">{{ $thread->username }}  {{$thread->teacherid}}</p>
                        </div>
                    </div>

                    {{-- Waktu --}}
                    <div class="text-gray-400">
                        {{ \Carbon\Carbon::parse($thread->created_at)->diffForHumans() }}
                    </div>
                </div>

                {{-- Tindakan Admin --}}
                @if(session('role') == 3)
                    <form action="{{ route('forum.threads.destroy', $thread->id) }}" method="POST" class="absolute bottom-4 right-4 delete-form">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="text-red-500 hover:text-red-700 flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4m-4 4v12m4-12v12M1 7h22"></path>
                            </svg>
                            Hapus
                        </button>
                    </form>
                @endif
            </div>
        @empty
        <div class="text-center py-16 text-gray-500">
            <p>Belum ada thread yang dibuat. Yuk mulai diskusi!</p>
        </div>
        @endforelse
    </div>
</div>

{{-- SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.querySelectorAll('.delete-form').forEach(form => {
        form.querySelector('button').addEventListener('click', function(e) {
            e.preventDefault();

            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: "Thread yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    // Tampilkan notifikasi jika ada session 'success'
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            confirmButtonText: 'OK',
            confirmButtonColor: '#3085d6',
        });
    @endif
</script>


<style>
    .thread-card {
        transition: all 0.3s ease-in-out;
        padding-bottom: 2.5rem;
    }

    .thread-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
    }

    .delete-form button {
        background-color: #f8d7da;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 500;
        transition: background-color 0.3s;
    }

    .delete-form button:hover {
        background-color: #f5c2c7;
    }
</style>
@endsection
