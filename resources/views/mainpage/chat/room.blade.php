@extends(session('role') == 2 ? 'layouts.pelajarPanel' : 'layouts.tutorPanel')

@section('content')
    <div class="p-4">
        {{-- Header Chat --}}
        <div class="bg-gray-200 p-4 flex items-center rounded-lg shadow">
            <button onclick="window.location.href='{{ route('chat.index') }}'" class="bg-blue-500 text-white px-3 py-1 rounded-lg mr-3">
            <i class="fas fa-arrow-left"></i>
            </button>
            <img src="{{ asset('storage/' . (session('role') == 2 ? $chatRoom->tutor->image : $chatRoom->student->image)) }}" 
                 alt="Profile Picture" class="w-12 h-12 rounded-full border-2 border-gray-400">
            <h1 class="text-xl font-semibold ml-3">
                {{ session('role') == 2 ? $chatRoom->tutor->username : $chatRoom->student->username }}
            </h1>
        </div>

        {{-- Chat Bubble --}}
        <div class="mt-4 bg-gray-100 p-4 rounded-lg h-96 overflow-y-auto border border-blue-400">
            @foreach($messages as $message)
                <div class="flex items-start mb-4 {{ $message->sender_id == session('id') ? 'justify-end' : '' }}">
                    {{-- PP di bubble chat (hanya untuk penerima) --}}
                    @if($message->sender_id != session('id'))
                        <img src="{{ asset('storage/' . $chatRoom->student->image) }}" 
                             alt="User Profile" class="w-8 h-8 rounded-full mr-2">
                    @endif
                    
                    {{-- Bubble Chat --}}
                    <div class="max-w-xs p-3 rounded-lg border 
                        {{ $message->sender_id == session('id') ? 'bg-blue-500 text-white border-blue-600' : 'bg-gray-200 text-black border-gray-300' }}">
                        
                        {{-- Jika pesan berupa gambar --}}
                        @if($message->image)
                            <img src="{{ asset('storage/' . $message->image) }}" 
                                class="w-40 h-auto rounded-lg mb-2 cursor-pointer"
                                onclick="openImagePopup('{{ asset('storage/' . $message->image) }}')">
                        @endif
                        
                        <p>{{ $message->message }}</p>
                        <span class="text-xs block mt-1 
                            {{ $message->sender_id == session('id') ? 'text-white' : 'text-gray-500' }}">
                            {{ \Carbon\Carbon::parse($message->created_at)->format('d M Y, H:i') }}
                        </span>
                    </div>


                    {{-- PP di bubble chat (untuk pengirim) --}}
                    @if($message->sender_id == session('id'))
                        <img src="{{ asset('storage/' . session('image')) }}" 
                             alt="My Profile" class="w-8 h-8 rounded-full ml-2">
                    @endif
                </div>
            @endforeach
        </div>

        {{-- Form Chat --}}
        <form id="chatForm" action="{{ route('chat.send', ['room_id' => $chatRoom->id]) }}" method="POST" enctype="multipart/form-data" class="mt-4">
            @csrf
            <div class="flex flex-col space-y-2">
                {{-- Preview Gambar di Dalam Textarea --}}
                <div id="imagePreviewContainer" class="hidden">
                    <img id="previewImage" class="w-full h-auto rounded-lg border mb-2">
                    <button type="button" id="removeImage" class="bg-red-500 text-white rounded px-2 py-1">
                        Hapus Gambar
                    </button>
                </div>

                <textarea name="message" id="messageInput" class="w-full p-2 border rounded-lg" placeholder="Tulis pesan..."></textarea>

                <div class="flex items-center space-x-2">
                <!-- Input untuk Gambar -->
                <input type="file" name="image" id="imageInput" class="hidden">
                <label for="imageInput" class="cursor-pointer px-4 py-2 bg-gray-300 text-black rounded-lg">
                    <i class="fas fa-image"></i>
                </label>

                <!-- Input untuk File -->
                <input type="file" name="attachment" id="fileInput" class="hidden">
                <label for="fileInput" class="cursor-pointer px-4 py-2 bg-gray-300 text-black rounded-lg">
                    <i class="fas fa-paperclip"></i>
                </label>

                <!-- Tombol Kirim -->
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>

            </div>
        </form>
    </div>

    {{-- Popup Image Viewer --}}
    <div id="imagePopup" class="fixed inset-0 bg-black bg-opacity-75 hidden flex items-center justify-center">
        <div class="relative">
            <img id="popupImage" class="max-w-full max-h-screen rounded-lg">
            <button class="absolute top-2 right-2 bg-red-500 text-white  px-2"
                    onclick="closeImagePopup()">X</button>
        </div>
    </div>

    {{-- Script untuk Enter, Preview Gambar, dan Reset Form --}}
    <script>
        // Submit form dengan tombol Enter
        document.getElementById('messageInput').addEventListener('keypress', function (e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                document.getElementById('chatForm').submit();
            }
        });

        // Fungsi untuk scroll otomatis ke chat terakhir
        function scrollToBottom() {
            let chatContainer = document.querySelector('.h-96.overflow-y-auto');
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }

        // Panggil fungsi saat halaman selesai dimuat
        document.addEventListener("DOMContentLoaded", function() {
            scrollToBottom();
        });
        // Preview Gambar dalam Textarea
        document.getElementById('imageInput').addEventListener('change', function() {
            let file = this.files[0];
            let preview = document.getElementById('previewImage');
            let container = document.getElementById('imagePreviewContainer');

            if (file) {
                let reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    container.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            } else {
                container.classList.add('hidden');
            }
        });
        // Preview File Attachment
        document.getElementById('fileInput').addEventListener('change', function() {
            let file = this.files[0];

            if (file) {
                console.log(file); // Debugging: cek apakah file masuk

                // Tampilkan nama file ke textarea (pastikan ID `messageInput` ada)
                let messageInput = document.getElementById('messageInput');
                if (messageInput) {
                    messageInput.value += ` [File: ${file.name}]`;
                } else {
                    console.error("Element dengan ID 'messageInput' tidak ditemukan.");
                }

                // Jika ada preview gambar
                if (file.type.startsWith("image/")) {
                    let reader = new FileReader();
                    reader.onload = function(e) {
                        let previewImage = document.getElementById('imagePreview');
                        let previewContainer = document.getElementById('imagePreviewContainer');
                        if (previewImage && previewContainer) {
                            previewImage.src = e.target.result;
                            previewContainer.classList.remove('hidden'); // Tampilkan preview
                        } else {
                            console.error("Preview container tidak ditemukan.");
                        }
                    };
                    reader.readAsDataURL(file);
                }
            }
        });


        // Hapus File Attachment
        document.getElementById('removeFile').addEventListener('click', function() {
            document.getElementById('fileInput').value = "";
            document.getElementById('filePreviewContainer').classList.add('hidden');
        });


        // Hapus Gambar yang Dipilih
        document.getElementById('removeImage').addEventListener('click', function() {
            document.getElementById('imageInput').value = "";
            document.getElementById('imagePreviewContainer').classList.add('hidden');
        });

        // Reset Form setelah Submit
        document.getElementById('chatForm').addEventListener('submit', function(e) {
            e.preventDefault(); // Mencegah reload bawaan agar efeknya terlihat
            let form = this;
            let formData = new FormData(form);

            // Debugging: cek apakah file masuk ke FormData
            for (let [key, value] of formData.entries()) {
                console.log(key, value);
            }

            fetch(form.action, {
                method: form.method,
                body: formData,
                headers: {
                    'Accept': 'application/json' // Hindari Laravel mengembalikan HTML jika ada error
                }
            }).then(response => response.json())
            .then(data => {
                if (data.success) {
                    form.reset(); // Reset input setelah pesan terkirim
                    document.getElementById('imagePreviewContainer').classList.add('hidden');

                    // Ambil chat container dan tambahkan pesan baru
                    let chatContainer = document.querySelector('.h-96.overflow-y-auto');
                    let newMessage = document.createElement('div');
                    newMessage.classList.add('flex', 'items-start', 'mb-4', 'justify-end');

                    let messageContent = `
                        <div class="max-w-xs p-3 rounded-lg border bg-blue-500 text-white border-blue-600">
                            <p>${data.message}</p>
                            <span class="text-xs text-gray-300 block mt-1">
                                ${new Date().toLocaleString('id-ID')}
                            </span>
                        </div>
                        <img src="${data.userImage}" alt="My Profile" class="w-8 h-8 rounded-full ml-2">
                    `;

                    // Jika ada gambar yang dikirim
                    if (data.imageUrl) {
                        messageContent = `
                            <div class="max-w-xs p-3 rounded-lg border bg-blue-500 text-white border-blue-600">
                                <p>${data.message}</p>
                                <img src="${data.imageUrl}" class="w-40 h-auto mt-2 rounded-lg border border-gray-300">
                                <span class="text-xs text-gray-300 block mt-1">
                                    ${new Date().toLocaleString('id-ID')}
                                </span>
                            </div>
                            <img src="${data.userImage}" alt="My Profile" class="w-8 h-8 rounded-full ml-2">
                        `;
                    }

                    newMessage.innerHTML = messageContent;
                    chatContainer.appendChild(newMessage);

                    // Scroll ke bawah setelah pesan masuk
                    setTimeout(() => {
                        chatContainer.scrollTop = chatContainer.scrollHeight;
                    }, 100);
                }
            }).catch(error => console.error('Error:', error));
        });


        // Buka Popup Gambar
        function openImagePopup(imageSrc) {
            document.getElementById('popupImage').src = imageSrc;
            document.getElementById('imagePopup').classList.remove('hidden');
        }

        // Tutup Popup Gambar
        function closeImagePopup() {
            document.getElementById('imagePopup').classList.add('hidden');
        }
    </script>
@endsection
