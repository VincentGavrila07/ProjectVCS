@extends('layouts.tutorPanel')

@section('content')
    <h2 class="text-2xl font-semibold mb-4">Edit Profile</h2>

    @if(session('success'))
        <div class="bg-green-500 text-white p-4 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @php
        $isIncomplete = empty(session('username')) || empty(session('price')) || empty(session('subjectClass')) || empty(session('image'));
    @endphp

    @if($isIncomplete)
        <div class="bg-red-500 text-white p-4 rounded mb-4">
            <p><strong>⚠️ Perhatian!</strong> Harap lengkapi semua data secepatnya.</p>
        </div>
    @endif

    <form action="{{ route('profile.update.tutor') }}" method="POST" enctype="multipart/form-data" class="max-w-lg mx-auto bg-white p-6 rounded shadow">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="username" class="block font-medium mb-1">Username</label>
            <input type="text" name="username" id="username" value="{{ old('username', session('username')) }}" class="w-full border px-3 py-2 rounded" required>
            @error('username')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="price" class="block font-medium mb-1">Harga</label>
            <input type="number" name="price" id="price" value="{{ old('price', session('price')) }}" class="w-full border px-3 py-2 rounded" required>
            @error('price')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="specialty" class="block font-medium mb-1">Keahlian</label>
            <select name="specialty" id="specialty" class="w-full border px-3 py-2 rounded" required>
                <option value="">Pilih Keahlian</option>
                @foreach($subjects as $subject)
                    <option value="{{ $subject->id }}" {{ session('subjectClass') == $subject->id ? 'selected' : '' }}>
                        {{ $subject->subjectName }}
                    </option>
                @endforeach
            </select>
            @error('specialty')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="photo" class="block font-medium mb-1">Upload Foto Profil</label>
            <input type="file" name="photo" id="photo" class="w-full">
            @error('photo')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror

            @if(session('image'))
                <div class="mt-2">
                    <p class="font-medium">Foto Saat Ini:</p>
                    <img src="{{ asset('storage/' . session('image')) }}" alt="Foto Profil" class="w-24 h-24 rounded-full">
                </div>
            @endif
        </div>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">
            Update Profile
        </button>
    </form>
@endsection
