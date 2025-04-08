@extends(session('role') == 2 ? 'layouts.pelajarPanel' : 'layouts.tutorPanel')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    {{-- Header Thread --}}
    <div class="bg-white p-8 rounded-2xl shadow-md border border-blue-100 mb-8">
        <div class="flex items-center justify-between mb-6">
            {{-- Tombol Kembali --}}
            <button onclick="window.history.back()"
                    class="bg-blue-500 text-white px-3 py-1 rounded-lg mr-3">
                <i class="fas fa-arrow-left"></i> Kembali
            </button>
        </div>

        {{-- Subject Thread --}}
        @if($thread->thread_subject)
            <span class="inline-block bg-blue-50 text-blue-600 text-sm font-medium rounded-full px-3 py-1 mb-3">
                {{ $thread->thread_subject }}
            </span>
        @else
            <span class="inline-block bg-gray-100 text-gray-600 text-sm font-medium rounded-full px-3 py-1 mb-3">
                General
            </span>
        @endif

        {{-- Judul --}}
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-4">{{ $thread->title }}</h1>

        {{-- User Info --}}
        <div class="flex items-center gap-4 mb-6">
            <img src="{{ asset('storage/' . $thread->image) }}"
                class="w-12 h-12 rounded-full border-4 border-blue-400 object-cover shadow-md"
                alt="Avatar {{ $thread->username }}">
            <div>
                <p class="text-gray-400 text-sm">Ditulis oleh</p>
                <p class="font-semibold text-gray-800">{{ $thread->username }} {{$thread->teacherid}}</p>

                @if ($thread->role == 1)
                    <p class="text-sm text-gray-500">{{ $thread->user_subject ?? 'Tidak ada subject' }}</p>
                @endif

                <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($thread->created_at)->diffForHumans() }}</p>
            </div>
        </div>

        {{-- Isi Thread --}}
        <div class="prose max-w-none text-gray-700 break-words">
            {!! nl2br(e($thread->content)) !!}
        </div>
    </div>

    {{-- Komentar --}}
    <div class="bg-white p-6 rounded-2xl shadow border border-gray-100 space-y-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">💬 Komentar</h2>

        @php
            $parentComments = $groupedPosts[null] ?? [];
        @endphp

        @foreach ($parentComments as $post)
            {{-- Komentar Utama --}}
            <div class="flex items-start gap-4 border-t pt-4">
                <img src="{{ asset('storage/' . $post->image) }}"
                    class="w-10 h-10 rounded-full object-cover border border-blue-300 shadow-sm"
                    alt="{{ $post->username }}">
                <div class="w-full">
                    <p class="font-medium text-gray-800">{{ $post->username }} {{$post->teacherid}}</p>
                    <p class="text-gray-700">{{ $post->content }}</p>
                    <p class="text-sm text-gray-400 mt-1">
                        {{ \Carbon\Carbon::parse($post->created_at)->diffForHumans() }}
                        <button onclick="replyTo('{{ $post->username }}', {{ $post->id }})"
                                class="ml-4 text-sm text-blue-500 font-medium hover:underline">
                            Reply
                        </button>
                        @if (isset($groupedPosts[$post->id]))
                            <button onclick="toggleReplies({{ $post->id }})"
                                    id="toggle-btn-{{ $post->id }}"
                                    data-count="{{ count($groupedPosts[$post->id]) }}"
                                    class="ml-4 text-sm text-gray-500 hover:text-blue-600">
                                Show Replies ({{ count($groupedPosts[$post->id]) }})
                            </button>
                        @endif
                    </p>

                    {{-- Container Balasan --}}
                    <div id="replies-{{ $post->id }}" class="mt-3 ml-6 hidden">
                        @foreach($groupedPosts[$post->id] ?? [] as $child)
                            <div class="flex items-start gap-3 mb-3">
                                <img src="{{ asset('storage/' . $child->image) }}"
                                    class="w-8 h-8 rounded-full object-cover border" alt="{{ $child->username }}">
                                <div class="bg-gray-50 px-4 py-2 rounded-xl w-full">
                                    <p class="font-medium text-gray-700 text-sm">{{ $child->username }} {{$child->teacherid}}</p>
                                    <p class="text-gray-700 text-sm">{{ $child->content }}</p>
                                    <div class="flex justify-between items-center mt-1">
                                        <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($child->created_at)->diffForHumans() }}</p>
                                        <button onclick="replyTo('{{ $child->username }}', {{ $post->id }})"
                                                class="text-xs text-blue-500 hover:underline">
                                            Reply
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach


        {{-- Form Komentar --}}
        <form id="commentForm" action="{{ route('forum.posts.store') }}" method="POST" class="pt-4 border-t mt-4">
            @csrf
            <input type="hidden" name="thread_id" value="{{ $thread->id }}">
            <input type="hidden" name="parent_id" id="parent_id" value="">
            <textarea name="content" id="commentContent" rows="3"
                      class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-blue-400 focus:ring-2 focus:outline-none"
                      placeholder="Tulis komentar kamu..." required></textarea>
            <div class="flex justify-end mt-2">
                <button type="button" id="cancelReplyBtn"
                        onclick="cancelReply()"
                        class="bg-transparent text-red-500 border border-black px-6 py-2 mr-2 rounded-full hover:bg-red-500 hover:text-white hover:border-white transition hidden">
                    Batal Balas
                </button>
                <button type="submit"
                        class="bg-blue-600 text-white px-6 py-2 rounded-full hover:bg-blue-700 transition">
                    Kirim Komentar
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Script --}}
<script>
    function replyTo(username, postId) {
        const textarea = document.getElementById('commentContent');
        const parentInput = document.getElementById('parent_id');
        const cancelBtn = document.getElementById('cancelReplyBtn');

        textarea.value = '@' + username + ' ';
        parentInput.value = postId;
        cancelBtn.classList.remove('hidden');

        textarea.focus();
        document.getElementById('commentForm').scrollIntoView({ behavior: 'smooth' });
    }

    function cancelReply() {
        document.getElementById('commentContent').value = '';
        document.getElementById('parent_id').value = '';
        document.getElementById('cancelReplyBtn').classList.add('hidden');
    }

    function toggleReplies(postId) {
        const replyBox = document.getElementById('replies-' + postId);
        const toggleBtn = document.getElementById('toggle-btn-' + postId);
        const count = toggleBtn.dataset.count;

        if (replyBox.classList.contains('hidden')) {
            replyBox.classList.remove('hidden');
            toggleBtn.textContent = 'Hide Replies';
        } else {
            replyBox.classList.add('hidden');
            toggleBtn.textContent = `Show Replies (${count})`;
        }
    }
</script>
@endsection
