@extends(session('role') == 2 ? 'layouts.pelajarPanel' : 'layouts.tutorPanel')

@section('content')
    <div class="max-w-3xl mx-auto p-6 bg-white shadow-lg rounded-lg h-[80vh] flex flex-col">
        <h1 class="text-2xl font-bold mb-5">Daftar Chat</h1>

        <!-- Search Bar -->
        <div class="relative mb-4">
            <input type="text" id="searchChat" class="w-full p-3 border rounded-lg text-lg" placeholder="Cari chat...">
            <i class="fas fa-search absolute right-4 top-3 text-gray-400 text-lg"></i>
        </div>

        <!-- Buat daftar chat bisa di-scroll -->
        <div class="flex-1 overflow-y-auto max-h-[60vh] pr-2">
            <ul id="chatList">
                @foreach($chatRooms as $room)
                    <li class="chat-item relative">
                        <a href="{{ route('chat.room', $room->id) }}" 
                        class="flex items-center p-4 border-b hover:bg-gray-100 rounded-lg transition">
                            <div class="w-16 h-16 overflow-hidden rounded-full border">
                                @php
                                    $profileImage = session('role') == 2 
                                        ? optional($room->tutor)->image 
                                        : optional($room->student)->image;
                                @endphp
                                <img src="{{ $profileImage ? asset('storage/' . $profileImage) : asset('images/user.jpg') }}" 
                                    alt="Profile Picture" class="w-16 h-16 rounded-full border-2 border-gray-400">
                            </div>
                            <div class="ml-4 flex-1">
                                <p class="font-semibold text-lg">{{ session('role') == 2 ? optional($room->tutor)->username : optional($room->student)->username }}</p>
                                <p class="text-base text-gray-600 truncate">{{ optional($room->lastMessage)->message ?? 'Belum ada pesan' }}</p>
                            </div>
                            <span class="text-sm text-gray-500">{{ optional($room->lastMessage)->created_at ? optional($room->lastMessage)->created_at->diffForHumans() : '' }}</span>

                            <!-- Notifikasi pesan baru -->
                            @if($room->newMessagesCount > 0)
                                <span class="absolute top-2 right-2 bg-red-500 text-white text-sm font-bold px-2 py-1 rounded-full">
                                    {{ $room->newMessagesCount }}
                                </span>
                            @endif
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>

    </div>

    <script>
        document.getElementById('searchChat').addEventListener('keyup', function() {
            let filter = this.value.toLowerCase();
            let chatItems = document.querySelectorAll('.chat-item');
            
            chatItems.forEach(item => {
                let username = item.querySelector('p.font-semibold').innerText.toLowerCase();
                if (username.includes(filter)) {
                    item.style.display = "block";
                } else {
                    item.style.display = "none";
                }
            });
        });
    </script>
@endsection
