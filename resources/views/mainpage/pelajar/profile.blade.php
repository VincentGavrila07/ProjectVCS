@extends('layouts.pelajarPanel')

@section('content')

@if(session('success'))
<div class="bg-green-500 text-white p-4 rounded mb-4">
    {{ session('success') }}
</div>
@endif

<form action="{{ route('profile.update.pelajar') }}" method="POST" enctype="multipart/form-data" class="max-w-lg mx-auto bg-white p-6 rounded shadow">
    @csrf
    @method('PUT')
    
    <div class="mb-4">
            <h2 class="text-2xl font-semibold mb-4">Edit Profile</h2>
            <label for="username" class="block font-medium mb-1">Username</label>
            <input type="text" name="username" id="username" value="{{ old('username', session('username')) }}" class="w-full border px-3 py-2 rounded" required>
            @error('username')
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
                    <img src="{{ asset('storage/' . session('image')) }}" alt="Foto Profil" class="w-24 h-24 rounded-full border-4 border-blue-400 shadow-md object-cover">
                </div>
            @endif
        </div>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">
            Update Profile
        </button>
    </form>
@endsection
