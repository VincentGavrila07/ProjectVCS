@extends(session('role') == 2 ? 'layouts.pelajarPanel' : 'layouts.tutorPanel')

@section('content')
<div class="p-4">
    <h1 class="text-2xl font-bold mb-6 text-gray-800">Daftar Chat Room</h1>

    {{-- Search & Filter --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-2 sm:space-y-0">
        <input 
            type="text" 
            id="searchInput" 
            placeholder="Cari nama pengguna..." 
            class="w-full sm:w-1/2 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none"
        >
        <div class="flex space-x-2">
            <button onclick="filterChats('all')" class="filter-btn bg-blue-100 text-blue-700 px-3 py-2 rounded-lg text-sm font-medium hover:bg-blue-200">
                Semua
            </button>
            <button onclick="filterChats('unread')" class="filter-btn bg-gray-100 text-gray-700 px-3 py-2 rounded-lg text-sm font-medium hover:bg-gray-200">
                Belum Dibaca
            </button>
        </div>
    </div>

    @if($chatRooms->isEmpty())
        <div class="text-center text-gray-500 mt-10">
            <p class="text-lg">Tidak ada chat room aktif saat ini.</p>
        </div>
    @else
        <div id="chatRoomList" class="space-y-4">
            @foreach($chatRooms as $room)
                @php
                    $isStudent = $room->student_id == session('id');
                    $partner = $isStudent ? $room->tutor : $room->student;
                    $lastMessage = $room->lastMessage ? Str::limit($room->lastMessage->message, 40) : 'Belum ada pesan';
                    $lastTime = $room->lastMessage ? $room->lastMessage->created_at->diffForHumans() : '';
                @endphp

                <div class="chat-room bg-white shadow-md p-4 rounded-xl flex justify-between items-center hover:shadow-lg transition"
                     data-username="{{ strtolower($partner->username) }}"
                     data-unread="{{ $room->newMessagesCount > 0 ? 'true' : 'false' }}">
                    <div class="flex items-center space-x-4">
                        <img src="{{ asset('storage/' . $partner->image) }}" class="w-14 h-14 rounded-full border-2 border-blue-300 shadow-sm object-cover">
                        <div>
                            <h2 class="font-semibold text-lg text-gray-800">{{ $partner->username }}</h2>
                            <p class="text-sm text-gray-500">{{ $lastMessage }}</p>
                            @if($lastTime)
                                <span class="text-xs text-gray-400">{{ $lastTime }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center space-x-3">
                        @if($room->newMessagesCount > 0)
                            <span class="inline-block bg-red-500 text-white text-xs font-semibold px-2 py-1 rounded-full">
                                {{ $room->newMessagesCount }} baru
                            </span>
                        @endif
                        <a href="{{ route('chatting.room', ['room_id' => $room->id]) }}"
                           class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium shadow">
                            Buka Chat
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

{{-- Script Filter/Search --}}
<script>
    const searchInput = document.getElementById('searchInput');
    const chatRooms = document.querySelectorAll('.chat-room');
    const filterButtons = document.querySelectorAll('.filter-btn');
    let activeFilter = 'all';

    function filterChats(mode) {
        activeFilter = mode;
        filterButtons.forEach(btn => btn.classList.remove('bg-blue-200'));
        if (mode === 'unread') {
            document.querySelector('[onclick="filterChats(\'unread\')"]').classList.add('bg-blue-200');
        } else {
            document.querySelector('[onclick="filterChats(\'all\')"]').classList.add('bg-blue-200');
        }
        applyFilters();
    }

    function applyFilters() {
        const query = searchInput.value.toLowerCase();
        chatRooms.forEach(room => {
            const username = room.dataset.username;
            const unread = room.dataset.unread === 'true';

            const matchesSearch = username.includes(query);
            const matchesFilter = activeFilter === 'all' || (activeFilter === 'unread' && unread);

            if (matchesSearch && matchesFilter) {
                room.style.display = 'flex';
            } else {
                room.style.display = 'none';
            }
        });
    }

    searchInput.addEventListener('input', applyFilters);
</script>
@endsection
