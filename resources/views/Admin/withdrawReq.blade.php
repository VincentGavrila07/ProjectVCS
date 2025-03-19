@extends('layouts.admin')

@section('content')
    <h2 class="text-2xl font-semibold mb-4">Daftar Permintaan Withdraw</h2>

    <!-- Panel Pencarian -->
    <form method="GET" action="{{ route('withdrawList') }}" class="mb-4">
        <div class="flex items-center">
            <input type="text" name="search" value="{{ request()->input('search') }}" placeholder="Cari berdasarkan ID, nama, rekening, atau jumlah uang" class="border px-4 py-2 rounded-lg mr-2 w-96">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </form>

    <!-- Tabel Data Withdraw -->
    <table class="table-auto border-collapse border border-gray-400 w-full">
        <thead>
            <tr class="bg-gray-900 text-white"> <!-- Ubah warna header -->
                <th class="border border-gray-700 px-4 py-2 text-left">ID</th>
                <th class="border border-gray-700 px-4 py-2 text-left">Nama</th>
                <th class="border border-gray-700 px-4 py-2 text-left">Bank</th>
                <th class="border border-gray-700 px-4 py-2 text-left">Rekening</th>
                <th class="border border-gray-700 px-4 py-2 text-left">Email</th>
                <th class="border border-gray-700 px-4 py-2 text-left">Jumlah</th>
                <th class="border border-gray-700 px-4 py-2 text-left">Status</th>
                <th class="border border-gray-700 px-4 py-2 text-left">Dibuat</th>
                <th class="border border-gray-700 px-4 py-2 text-left">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($withdraws as $withdraw)
                <tr class="bg-gray-100"> <!-- Warna latar belakang baris -->
                    <td class="border border-gray-300 px-4 py-2">{{ $withdraw->id }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $withdraw->username }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $withdraw->bank_name }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $withdraw->account_number }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $withdraw->email }}</td>
                    <td class="border border-gray-300 px-4 py-2">Rp {{ number_format($withdraw->amount, 0, ',', '.') }}</td>
                    <td class="border border-gray-300 px-4 py-2 text-center">
                        @php
                            $statusColors = [
                                'processing' => 'bg-yellow-500 text-white',
                                'done' => 'bg-green-500 text-white',
                                'canceled' => 'bg-red-500 text-white'
                            ];
                        @endphp
                        <span class="px-3 py-1 rounded-lg {{ $statusColors[$withdraw->status] ?? 'bg-gray-500 text-white' }}">
                            {{ ucfirst($withdraw->status) }}
                        </span>
                    </td>
                    <td class="border border-gray-300 px-4 py-2">{{ $withdraw->created_at }}</td>
                    <td class="border border-gray-300 px-4 py-2">
                        <button onclick="openModal({{ $withdraw->id }}, '{{ $withdraw->username }}', '{{ $withdraw->status }}')" 
                            class="bg-yellow-500 text-white px-3 py-1 rounded-lg">
                            <i class="fas fa-edit"></i>
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center py-4 text-gray-600">Tidak ada permintaan withdraw.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Modal Edit Withdraw -->
    <div id="withdrawModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 hidden flex items-center justify-center" onclick="closeModal(event)">
        <div class="bg-white rounded-lg p-6 w-96 shadow-lg" onclick="event.stopPropagation()">
            <h3 class="text-lg font-semibold mb-4">Edit Status Withdraw</h3>
            <form id="withdrawForm" method="POST">
                @csrf
                @method('PUT')

                <input type="hidden" id="withdrawId">

                <div class="mb-4">
                    <label class="block text-gray-700">Nama:</label>
                    <input type="text" id="withdrawName" class="border px-4 py-2 rounded-lg w-full" disabled>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700">Status:</label>
                    <select id="withdrawStatus" name="status" class="border px-4 py-2 rounded-lg w-full">
                        <option value="processing">Processing</option>
                        <option value="done">Done</option>
                        <option value="canceled">Canceled</option>
                    </select>
                </div>

                <div class="flex justify-end">
                    <button type="button" onclick="closeModal()" class="bg-gray-400 text-white px-4 py-2 rounded-lg mr-2">Batal</button>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $withdraws->links() }}
    </div>
@endsection

<script>
    function openModal(id, name, status) {
        document.getElementById('withdrawId').value = id;
        document.getElementById('withdrawName').value = name;
        document.getElementById('withdrawStatus').value = status;

        let form = document.getElementById('withdrawForm');
        form.action = "/withdraw/update/" + id;

        document.getElementById('withdrawModal').classList.remove('hidden');
    }

    function closeModal(event) {
        if (!event || event.target === document.getElementById('withdrawModal')) {
            document.getElementById('withdrawModal').classList.add('hidden');
        }
    }

    document.getElementById('withdrawForm').addEventListener('submit', function(event) {
        event.preventDefault();
        let id = document.getElementById('withdrawId').value;
        let status = document.getElementById('withdrawStatus').value;

        fetch(`/withdraw/update/${id}`, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ status: status })
        })
        .then(response => response.json())
        .then(data => {
            alert('Status berhasil diperbarui!');
            location.reload();
        })
        .catch(error => console.error('Error:', error));
    });
</script>
