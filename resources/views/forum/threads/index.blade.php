@extends(session('role') == 2 ? 'layouts.pelajarPanel' : 'layouts.tutorPanel')

@section('content')
<div class="py-4 px-4 max-w-5xl mx-auto">

    {{-- Sticky Header (dalam content) --}}
    <div class="sticky top-0 bg-white py-4 px-6 z-20 border-b border-gray-200 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <h2 class="text-xl font-bold text-gray-800">Thread</h2>
            
            <form action="{{ route('forum.threads.index') }}" method="GET" class="flex w-full md:w-2/3">
                <input type="text" name="search" placeholder="Cari tutor berdasarkan nama, judul forum, atau mata pelajaran..."
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
            <div class="bg-white p-6 rounded-2xl shadow border-l-4 border-blue-100 hover:shadow-md transition duration-300 thread-card">
                    {{-- Badge Kategori --}}
                    @if($thread->thread_subject )
            <span class="inline-block bg-blue-50 text-blue-600 text-sm font-medium rounded-full px-3 py-1 mb-3">
                {{ $thread->thread_subject  }}
            </span>
        @else
            <span class="inline-block bg-gray-100 text-gray-600 text-sm font-medium rounded-full px-3 py-1 mb-3">
                General
            </span>
        @endif


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
                        
                        @if($thread->role == 1)
                            <p class="text-sm text-gray-500">
                                Mengajar {{ $thread->user_subject ?? 'Tidak ada subject' }}
                            </p>
                        @endif
                    </div>
                </div>


                {{-- Waktu --}}
                <div class="flex items-center gap-1 text-gray-400">
                    <i class="bi bi-clock"></i>
                    <span>{{ \Carbon\Carbon::parse($thread->created_at)->diffForHumans() }}</span>
                </div>
            </div>
        </div>
        @empty
        {{-- Jika kosong --}}
        <div class="text-center py-16 text-gray-500">
            <i class="bi bi-chat-dots text-4xl mb-4"></i>
            <p class="mb-4">Belum ada thread yang dibuat. Yuk mulai diskusi!</p>
            <a href="{{ route('forum.threads.create') }}"
               class="inline-block border border-blue-500 text-blue-500 hover:bg-blue-500 hover:text-white transition px-6 py-2 rounded-full">
                Buat Thread Pertama
            </a>
        </div>
        @endforelse
    </div>
</div>

{{-- Style --}}
<style>
    .thread-card {
        transition: all 0.3s ease-in-out;
        border-left: 5px solid #0d6efd20;
        border-radius: 16px;
    }

    .thread-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
    }

    .rounded-circle {
        object-fit: cover;
    }
</style>
@endsection
