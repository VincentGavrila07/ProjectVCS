@extends('layouts.admin')

@section('content')
    <h2 class="text-2xl font-semibold mb-4">Daftar User</h2>

    <!-- Panel Pencarian -->
    <form method="GET" action="{{ route('tutorList') }}" class="mb-4">
        <div class="flex items-center">
            <input type="text" name="search" value="{{ request()->input('search') }}" placeholder="Cari berdasarkan id, nama, role, atau email" class="border px-4 py-2 rounded-lg mr-2 w-96">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-search"></i> <!-- Ikon search -->
            </button>
        </div>
    </form>

    <!-- Tabel Data User -->
    <form method="GET" action="{{ route('tutorList') }}">
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
                        Tutor ID
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
                        <td class="border border-gray-300 px-4 py-2">{{ $user->TeacherId }}</td>
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

<!-- Pastikan SweetAlert2 sudah di-include, misalnya di <head> layout admin -->
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
                // Lakukan AJAX request menggunakan fetch
                fetch("{{ url('/admin/tutor') }}/" + userId, {
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
                            // Reload halaman untuk melihat perubahan
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
                        text: 'Terjadi kesalahan saat menghapus tutor.'
                    });
                });
            }
        });
        return false; // Mencegah submit form biasa
    }
</script>


