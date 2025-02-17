@extends('layouts.admin')

@section('content')
    <h2 class="text-2xl font-semibold mb-4">Daftar Pelajar</h2>

    <!-- Panel Pencarian -->
    <form method="GET" action="{{ route('pelajarList') }}" class="mb-4">
        <div class="flex items-center">
            <input type="text" name="search" value="{{ request()->input('search') }}" placeholder="Cari berdasarkan nama, role, atau email" class="border px-4 py-2 rounded-lg mr-2 w-96">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </form>

    <!-- Tabel Data Pelajar -->
    <form method="GET" action="{{ route('pelajarList') }}">
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
                    <th class="border border-gray-300 px-4 py-2 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td class="border border-gray-300 px-4 py-2">{{ $user->id }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $user->username }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $user->role }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $user->email }}</td>
                        <td class="border border-gray-300 px-2 py-2 text-center">
                            <button onclick="return confirmDelete('{{ $user->id }}');" class="bg-red-500 text-white px-4 py-2 rounded-lg">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </form>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $users->links() }}
    </div>

    @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 1500
            });
        </script>
    @endif

@endsection

<style>
    th {
        background-color: #2d3748;
        color: #fff;
    }
</style>

<!-- Pastikan SweetAlert2 sudah di-include -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function confirmDelete(userId) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Anda tidak akan bisa mengembalikan data ini!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch("{{ url('/admin/pelajar') }}/" + userId, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                })
                .then(response => response.json())
                .then(data => {
                    console.log("Response:", data);
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: data.message,
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: data.message
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: 'Terjadi kesalahan saat menghapus pelajar.'
                    });
                });
            }
        });
        return false;
    }
</script>
