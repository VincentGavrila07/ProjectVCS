@extends('layouts.admin')

@section('content')
    <h2 class="text-2xl font-semibold mb-4">Daftar User</h2>

    <!-- Panel Pencarian -->
    <form method="GET" action="{{ route('userList') }}" class="mb-4">
        <div class="flex items-center">
            <input type="text" name="search" value="{{ request()->input('search') }}" placeholder="Cari berdasarkan nama, role, atau email" class="border px-4 py-2 rounded-lg mr-2 w-96">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-search"></i> <!-- Ikon search -->
            </button>
        </div>
    </form>

    <!-- Tabel Data User -->
    <form method="GET" action="{{ route('userList') }}">
        <table class="table-auto border-collapse border border-gray-400 w-full">
            <thead>
                <tr>
                    <th class="border border-gray-300 px-4 py-2 text-left">
                        ID
                        <button type="submit" name="sort" value="id" class="ml-2 text-blue-500">
                            <i class="fas fa-sort"></i>
                        </button>
                    </th>
                    <th class="border border-gray-300 px-4 py-2 text-left">
                        Name
                        <button type="submit" name="sort" value="username" class="ml-2 text-blue-500">
                            <i class="fas fa-sort"></i>
                        </button>
                    </th>
                    <th class="border border-gray-300 px-4 py-2 text-left">
                        Role
                        <button type="submit" name="sort" value="role" class="ml-2 text-blue-500">
                            <i class="fas fa-sort"></i>
                        </button>
                    </th>
                    <th class="border border-gray-300 px-4 py-2 text-left">
                        Email
                        <button type="submit" name="sort" value="email" class="ml-2 text-blue-500">
                            <i class="fas fa-sort"></i>
                        </button>
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td class="border border-gray-300 px-4 py-2">{{ $user->id }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $user->username }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $user->role }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $user->email }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </form>
    
    <!-- Pagination -->
    <div class="mt-4">
        {{ $users->links() }} <!-- Untuk pagination -->
    </div>
@endsection

<style>
    th.button {
        cursor: pointer;
    }

    th {
        background-color:#2d3748; /* Warna abu-abu muda, seperti bg-gray-200 */
        color: #fff; /* Warna teks gelap untuk kontras */
    }
</style>