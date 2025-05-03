@extends(session('role') == 2 ? 'layouts.pelajarPanel' : 'layouts.tutorPanel')

@section('content')
<div class="p-4">
    <h1 class="text-xl font-semibold mb-4">Chat Room</h1>

    {{-- Header Chat --}}
    <div class="bg-gray-200 p-4 flex items-center rounded-lg shadow">
        <button onclick="window.location.href='{{ route('chatting.index') }}'" class="bg-blue-500 text-white px-3 py-1 rounded-lg mr-3">
            <i class="fas fa-arrow-left"></i>
        </button>
        <img src="{{ asset('storage/' . ($chatRoom->student_id == session('id') ? $chatRoom->tutor->image : $chatRoom->student->image)) }}" class="w-14 h-14 rounded-full border-2 border-blue-300 shadow-sm object-cover">
        <h1 class="text-xl font-semibold ml-3">{{ $chatRoom->student_id == session('id') ? $chatRoom->tutor->username : $chatRoom->student->username }}</h1>
    </div>

    {{-- Chat Bubble --}}
    <div id="chat-messages" class="mt-4 bg-gray-100 p-4 rounded-lg h-96 overflow-y-auto border border-blue-400">
        @foreach($messages as $message)
            <div class="flex items-start mb-4 {{ $message->sender_id == session('id') ? 'justify-end' : '' }}">
                @if($message->sender_id != session('id'))
                    <img src="{{ asset('storage/' . $message->sender->image) }}"class="w-10 h-10 rounded-full border-2 border-blue-300 shadow-sm object-cover">
                @endif
                <div class="max-w-xs p-3 rounded-lg border {{ $message->sender_id == session('id') ? 'bg-blue-500 text-white border-blue-600' : 'bg-gray-200 text-black border-gray-300' }}">
                    @if($message->image)
                        <img src="{{ asset('storage/' . $message->image) }}" class="w-40 h-auto rounded-lg mb-2">
                    @endif
                    <p>{{ $message->message }}</p>
                    <span class="text-xs block mt-1 {{ $message->sender_id == session('id') ? 'text-white' : 'text-gray-500' }}">
                        {{ \Carbon\Carbon::parse($message->created_at)->format('d M Y, H:i') }}
                    </span>
                </div>
                @if($message->sender_id == session('id'))
                    <img src="{{ asset('storage/' . session('image')) }}" class="w-10 h-10 rounded-full border-2 border-blue-300 shadow-sm object-cover">
                @endif
            </div>
        @endforeach
    </div>

    {{-- Form Chat --}}
    <form id="chat-form" class="mt-4" enctype="multipart/form-data">
        @csrf
        <div class="flex flex-col space-y-2">
            <textarea name="message" id="message" class="w-full p-2 border rounded-lg" placeholder="Tulis pesan..."></textarea>
            <div class="flex items-center space-x-2">
                <input type="file" name="image" class="hidden" id="imageInput">
                <label for="imageInput" class="cursor-pointer px-4 py-2 bg-gray-300 text-black rounded-lg">
                    <i class="fas fa-image"></i>
                </label>
                <input type="file" name="file" class="hidden" id="fileInput">
                <label for="fileInput" class="cursor-pointer px-4 py-2 bg-gray-300 text-black rounded-lg">
                    <i class="fas fa-paperclip"></i>
                </label>
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </div>
    </form>
</div>

{{-- Scripts --}}
<script src="https://js.pusher.com/7.2/pusher.min.js"></script>
<script>
    const userId = {{ session('id') }};
    const roomId = {{ $chatRoom->id }};
    const userImage = "{{ asset('storage/' . session('image')) }}";
    const form = document.getElementById('chat-form');
    const chatMessages = document.getElementById('chat-messages');
    chatMessages.scrollTop = chatMessages.scrollHeight;


    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(form);

        const response = await fetch("{{ route('chatting.send', ['room_id' => $chatRoom->id]) }}", {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });

        if (response.ok) {
            form.reset();
        }
    });

    Pusher.logToConsole = false;

    const pusher = new Pusher('{{ env("PUSHER_APP_KEY") }}', {
        cluster: '{{ env("PUSHER_APP_CLUSTER") }}',
        encrypted: true
    });

    const channel = pusher.subscribe('chatroom.' + roomId);
    channel.bind('message_sent', function(data) {
        const isMine = data.sender_id == userId;

        const bubble = document.createElement('div');
        bubble.classList.add('flex', 'items-start', 'mb-4');
        if (isMine) bubble.classList.add('justify-end');

        bubble.innerHTML = `
            ${!isMine ? `<img src="${data.sender_image}" class="w-8 h-8 rounded-full mr-2">` : ''}
            <div class="max-w-xs p-3 rounded-lg border ${isMine ? 'bg-blue-500 text-white border-blue-600' : 'bg-gray-200 text-black border-gray-300'}">
                ${data.image ? `<img src="${data.image}" class="w-40 h-auto rounded-lg mb-2">` : ''}
                <p>${data.message}</p>
                <span class="text-xs block mt-1 ${isMine ? 'text-white' : 'text-gray-500'}">${data.created_at}</span>
            </div>
            ${isMine ? `<img src="${userImage}" class="w-8 h-8 rounded-full ml-2">` : ''}
        `;

        chatMessages.appendChild(bubble);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    });
</script>
@endsection
