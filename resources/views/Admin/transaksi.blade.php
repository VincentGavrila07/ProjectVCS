@extends('layouts.admin')

@section('content')
    <h2 class="text-2xl font-semibold mb-4">Daftar Transaksi</h2>

    <!-- Panel Pencarian -->
    <form method="GET" action="{{ route('transaksiList') }}" class="mb-4">
        <div class="flex items-center">
            <input type="text" name="search" value="{{ request()->input('search') }}" placeholder="Cari berdasarkan  id, student email, tutor email" class="border px-4 py-2 rounded-lg mr-2 w-96">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-search"></i> <!-- Ikon search -->
            </button>
        </div>
    </form>

    <!-- Tabel Data Transactions -->
    <table class="table-auto border-collapse border border-gray-400 w-full">
        <thead>
            <tr class="bg-gray-800 text-white">
                <th class="border border-gray-300 px-4 py-2">ID</th>
                <th class="border border-gray-300 px-4 py-2">Student Email</th>
                <th class="border border-gray-300 px-4 py-2">Tutor Email</th>
                <th class="border border-gray-300 px-4 py-2">Amount</th>
                <th class="border border-gray-300 px-4 py-2">Status</th>
                <th class="border border-gray-300 px-4 py-2">Status VCS</th>
                <th class="border border-gray-300 px-4 py-2">Dibuat</th>
                <th class="border border-gray-300 px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transactions as $transaction)
                <tr class="bg-gray-100">
                    <td class="border border-gray-300 px-4 py-2">{{ $transaction->id }}</td>
                    <td class="border border-gray-300 px-4 py-2">
                        {{ $transaction->student->email ?? 'Tidak Diketahui' }}
                    </td>
                    <td class="border border-gray-300 px-4 py-2">
                        {{ $transaction->tutor->email ?? 'Tidak Diketahui' }}
                    </td>
                    <td class="border border-gray-300 px-4 py-2">{{ number_format($transaction->amount, 2) }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ ucfirst($transaction->status) }}</td>
                    <td class="border border-gray-300 px-4 py-2">
                        @php
                            $createdTime = strtotime($transaction->created_at);
                            $currentTime = time();
                            if ($transaction->status === 'canceled') {
                                $statusVCS = 'Canceled';
                            } else {
                                $statusVCS = ($currentTime - $createdTime) <= (65 * 60) ? 'On Going' : 'Done';
                            }
                        @endphp
                        {{ $statusVCS }}
                    </td>
                    <td class="border border-gray-300 px-4 py-2">{{ $transaction->created_at->format('Y-m-d H:i') }}</td>
                    <td class="border border-gray-300 px-4 py-2 text-center">
                        <button onclick="confirmDelete('{{ $transaction->id }}')" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">
                            <i class="fas fa-trash"></i> 
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $transactions->links() }} <!-- Untuk pagination -->
    </div>
@endsection

<style>
    th.button {
        cursor: pointer;
    }

    th {
        background-color:#2d3748; /* Warna abu-abu muda */
        color: #fff; /* Warna teks putih */
    }
</style>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function confirmDelete(transactionId) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Anda tidak akan bisa mengembalikan data ini!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch("{{ route('transactions.destroy', '') }}/" + transactionId, { 
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
                            text: 'Transaksi berhasil dihapus.',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            window.location.reload(); // Refresh halaman setelah sukses
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Terjadi kesalahan saat menghapus transaksi.'
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
