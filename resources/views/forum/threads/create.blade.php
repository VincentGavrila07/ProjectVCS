@extends(
    session('role') == 3 ? 'layouts.admin' : 
    (session('role') == 2 ? 'layouts.pelajarPanel' : 'layouts.tutorPanel')
)
@section('content')
<div class="w-full px-4 md:px-0 py-8">
    <div class="max-w-3xl mx-auto bg-white p-8 rounded-2xl shadow-lg">
        <div class="flex items-center justify-between mb-6">
            {{-- Tombol Kembali --}}
            <button onclick="window.history.back()"
            class="bg-blue-500 text-white px-3 py-1 rounded-lg mr-3">
                <i class="fas fa-arrow-left"></i> Kembali
            </button>
        </div>
        <h2 class="text-2xl md:text-3xl font-bold text-blue-600 flex items-center gap-2 mb-8">
            📝 Buat Thread Baru
        </h2>

        <form action="{{ route('forum.threads.store') }}" method="POST" class="space-y-6">
            @csrf

            {{-- Judul --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Judul Thread</label>
                <input type="text" name="title" placeholder="Contoh: Bagaimana cara memahami materi X?"
                    class="w-full px-4 py-3 rounded-xl border border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-400 focus:outline-none"
                    required>
            </div>

            {{-- Kategori --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Kategori</label>
                <select name="subject_id"
                    class="w-full px-4 py-3 rounded-xl border border-gray-300 shadow-sm bg-white focus:ring-2 focus:ring-blue-400 focus:outline-none text-gray-700">
                    <option value="" selected hidden>Pilih Kategori</option>
                    <option value="">General</option> {{-- General tidak punya id --}}
                    @foreach ($subjects as $subject)
                        <option value="{{ $subject->id }}">{{ $subject->subjectName }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Isi Thread dengan Label Tengah --}}
            <div>
                <label class="block mb-1 text-sm font-semibold text-gray-700">Thread</label>
                <textarea name="content" rows="7"
                    class="w-full px-4 py-4 rounded-xl border border-blue-400 text-gray-800 shadow-sm focus:ring-2 focus:ring-blue-400 focus:outline-none resize-none"
                    placeholder="Tulis pertanyaan atau diskusi kamu di sini..." required></textarea>
            </div>

            {{-- Tombol --}}
            <div class="flex justify-end">
                <button type="submit"
                    class="bg-blue-600 text-white px-6 py-2 rounded-full font-semibold shadow-md hover:bg-blue-700 transition">
                    Buat Thread
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
