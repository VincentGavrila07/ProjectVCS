@extends('layouts.pelajarPanel')

@section('content')

    <div class="bg-white shadow-lg rounded-lg p-8">
        <h1 class="text-2xl font-bold text-gray-800">Selamat Datang, </h1>
        <p>Username : {{ session('username') }}</p>
        <p class="text-gray-600 mt-2">Email : <span class="font-semibold">{{ session('email') }}</span></p>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="mt-4 inline-block bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">Logout</button>
        </form>
    </div>
@endsection
