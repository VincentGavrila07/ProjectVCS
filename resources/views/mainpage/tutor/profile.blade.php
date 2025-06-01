@extends('layouts.tutorPanel')

@section('content')

<div class="min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-5xl bg-[#1f2937] text-white p-8 rounded-lg shadow-lg">
        
        @if(session('success'))
            <div class="bg-green-500 text-white p-4 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @php
            $isIncomplete = empty(session('username')) || empty(session('price')) || empty(session('subjectClass')) || empty(session('image'));
        @endphp

        @if($isIncomplete)
            <div class="bg-red-600 text-white p-4 rounded mb-4">
                <p><strong>⚠️ Perhatian!</strong> Harap lengkapi semua data secepatnya.</p>
            </div>
        @endif

        <h2 class="text-3xl font-semibold mb-6 border-b border-gray-700 pb-2">Edit Profile</h2>

        <form action="{{ route('profile.update.tutor') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid md:grid-cols-2 gap-10 items-start">
                {{-- Foto Profil --}}
                <div class="flex flex-col items-center text-center">
                    @if(session('image'))
                        <img src="{{ asset('storage/' . session('image')) }}" 
                            alt="Foto Profil" 
                            class="w-40 h-40 rounded-full border-4 border-blue-400 shadow-md object-cover mb-4">
                    @else
                        <div class="w-40 h-40 rounded-full bg-gray-700 flex items-center justify-center mb-4">
                            <span class="text-xl text-gray-400">No Image</span>
                        </div>
                    @endif

                    <label for="photo" class="block text-sm font-medium mb-2">Upload Foto Profil</label>
                    <input type="file" name="photo" id="photo" class="w-full text-sm text-gray-300 file:mr-4 file:py-2 file:px-4
                        file:rounded-full file:border-0 file:text-sm file:font-semibold
                        file:bg-blue-500 file:text-white hover:file:bg-blue-600" />
                    @error('photo')
                        <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Form Field --}}
                <div>
                    {{-- Username --}}
                    <div class="mb-6">
                        <label for="username" class="block text-sm font-medium mb-1">Username</label>
                        <input type="text" name="username" id="username"
                            value="{{ old('username', session('username')) }}"
                            class="w-full bg-[#2A2A3B] border border-gray-600 text-white px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required>
                        @error('username')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Price --}}
                    <div class="mb-6">
                        <label for="price" class="block text-sm font-medium mb-1">Harga</label>
                        <input type="number" name="price" id="price"
                            value="{{ old('price', session('price')) }}"
                            class="w-full bg-[#2A2A3B] border border-gray-600 text-white px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required>
                        @error('price')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Specialty --}}
                    <div class="mb-6">
                        <label for="specialty" class="block text-sm font-medium mb-1">Keahlian</label>
                        <select name="specialty" id="specialty"
                            class="w-full bg-[#2A2A3B] border border-gray-600 text-white px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required>
                            <option value="">Pilih Keahlian</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ session('subjectClass') == $subject->id ? 'selected' : '' }}>
                                    {{ $subject->subjectName }}
                                </option>
                            @endforeach
                        </select>
                        @error('specialty')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Submit --}}
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-6 py-2 rounded transition w-full md:w-auto">
                        Update Profile
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection
