@extends('layouts.admin')

@section('content')
    <h2 class="text-2xl font-semibold mb-4">Daftar Subject</h2>

    {{-- Form Pencarian --}}
    <form method="GET" action="{{ route('subjectList') }}" class="mb-4">
        <div class="flex items-center">
            <input type="text" name="search" value="{{ request()->input('search') }}" placeholder="Cari berdasarkan ID, dan Nama Subject" class="border px-4 py-2 rounded-lg mr-2 w-96">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </form>

    {{-- Form Tambah Subject --}}
    <div class="mb-4 bg-white p-4 rounded-lg shadow">
        <h3 class="text-lg font-semibold mb-2">Tambah Subject</h3>
        <form method="POST" action="{{ route('subject.store') }}">
            @csrf
            <div class="flex items-center">
                <input type="text" name="subjectName" placeholder="Nama Subject" class="border px-4 py-2 rounded-lg mr-2 w-96" required>
                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-lg">
                    <i class="fas fa-plus"></i> Tambah
                </button>
            </div>
        </form>
    </div>

    {{-- Tabel Subject --}}
    <table class="table-auto border-collapse border border-gray-400 w-full">
        <thead>
            <tr class="bg-gray-800 text-white">
                <th class="border border-gray-300 px-4 py-2">ID</th>
                <th class="border border-gray-300 px-4 py-2">Name</th>
                <th class="border border-gray-300 px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($subject as $sub)
                <tr class="bg-gray-100">
                    <td class="border border-gray-300 px-4 py-2">{{ $sub->id }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $sub->subjectName }}</td>
                    <td class="border border-gray-300 px-4 py-2 text-center">
                        {{-- Tombol Edit --}}
                        <button onclick="editSubject('{{ $sub->id }}', '{{ $sub->subjectName }}')" class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600 mr-2">
                            <i class="fas fa-edit"></i>
                        </button>

                        {{-- Tombol Hapus --}}
                        <button onclick="confirmDelete('{{ $sub->id }}')" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">
                            <i class="fas fa-trash"></i> 
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $subject->links() }}
    </div>
@endsection

{{-- SweetAlert untuk Modal Edit dan Konfirmasi Hapus --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function editSubject(subjectId, subjectName) {
        Swal.fire({
            title: 'Edit Subject',
            input: 'text',
            inputValue: subjectName,
            showCancelButton: true,
            confirmButtonText: 'Simpan',
            cancelButtonText: 'Batal',
            inputValidator: (value) => {
                if (!value) {
                    return 'Nama Subject tidak boleh kosong!';
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                fetch("{{ route('subject.update', '') }}/" + subjectId, { 
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ subjectName: result.value })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Subject berhasil diperbarui.',
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
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: 'Terjadi kesalahan. Silakan coba lagi.'
                    });
                });
            }
        });
    }

    function confirmDelete(subjectId) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data ini tidak bisa dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch("{{ route('subject.destroy', '') }}/" + subjectId, { 
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Subject berhasil dihapus.',
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
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: 'Terjadi kesalahan. Silakan coba lagi.'
                    });
                });
            }
        });
    }
</script>
