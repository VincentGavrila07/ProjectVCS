@forelse($tutors as $tutor)
    <div class="bg-white shadow-lg rounded-xl p-4 flex flex-col items-center text-center border border-gray-200">
        @php
            $tutorImage = $tutor->image ? asset('storage/' . $tutor->image) : asset('images/user.jpg');
        @endphp

        <img src="{{ $tutorImage }}" 
            alt="Tutor Image" 
            class="w-24 h-24 object-cover rounded-full border-2 border-blue-500 shadow-md">

        <h3 class="text-lg font-semibold mt-3">{{ $tutor->username }}</h3>
        <span class="text-sm text-blue-500 font-medium">Tutor ID: {{ $tutor->TeacherId }}</span>
        
        <p class="text-sm text-gray-500 mt-1 font-bold">{{ $tutor->subject_name ?? '-' }}</p>

        <div class="flex justify-center items-center mt-2 text-gray-500 text-sm">
            @if (\Carbon\Carbon::parse($tutor->created_at)->diffInDays(now()) == 0)
                <span class="font-bold">Baru saja bergabung</span>
            @else
                <span class="font-bold">Sejak {{ \Carbon\Carbon::parse($tutor->created_at)->diffInDays(now()) }} hari yang lalu</span>
            @endif
            <span class="ml-3">👍 97%</span>
        </div>

        <p class="text-sm text-gray-700 font-medium mt-2">
            {{ $tutor->price ? 'Rp ' . number_format($tutor->price, 0, ',', '.') . '/jam' : 'Gratis' }}
        </p>

        <a href="{{ route('chat.create', ['tutor_id' => $tutor->id]) }}" 
            class="mt-3 px-4 py-2 bg-blue-500 text-white text-sm font-semibold rounded-lg shadow-md hover:bg-blue-600 transition duration-200 cursor-pointer">
            💬 Chat Sekarang
        </a>

        <a href="#" 
            onclick="sewaTutor({{ $tutor->id }})" 
            class="mt-3 px-4 py-2 bg-green-500 text-white text-sm font-semibold rounded-lg shadow-md hover:bg-green-600 transition duration-200 cursor-pointer">
            🛒 Sewa Sekarang
        </a>
    </div>
@empty
    <p class="text-center col-span-4 text-gray-500">Tidak ada tutor yang ditemukan.</p>
@endforelse
