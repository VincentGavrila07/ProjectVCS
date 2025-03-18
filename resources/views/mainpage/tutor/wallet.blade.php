@extends('layouts.tutorPanel')

@section('content')
<div class="container mx-auto p-6">
    <div class="bg-white shadow-md rounded-lg p-6">
        <h2 class="text-2xl font-semibold text-gray-800">Wallet Saya</h2>
        <p class="text-gray-600 mt-2">Saldo saat ini:</p>
        <div class="text-3xl font-bold text-blue-600 mt-2">
            Rp {{ number_format($wallet->balance, 0, ',', '.') }}
        </div>

        <div class="mt-4">
            <button onclick="openWithdrawModal()" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">
                Tarik Dana
            </button>
        </div>
    </div>
</div>

    <!-- Withdraw History Section -->
    <div class="bg-white shadow-md rounded-lg p-6 mt-6">
        <h2 class="text-2xl font-semibold text-gray-800">Riwayat Withdraw</h2>

        <table class="table-auto border-collapse border border-gray-400 w-full mt-4">
            <thead>
                <tr class="bg-gray-800 text-white">
                    <th class="border border-gray-300 px-4 py-2 text-left">ID</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Bank</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Jumlah</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Status</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Dibuat</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($withdraws as $withdraw)
                    <tr class="bg-gray-100">
                        <td class="border border-gray-300 px-4 py-2">{{ $withdraw->id }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $withdraw->bank_name }}</td>
                        <td class="border border-gray-300 px-4 py-2">Rp {{ number_format($withdraw->amount, 0, ',', '.') }}</td>
                        <td class="border border-gray-300 px-4 py-2 text-center">
                            <span class="px-3 py-1 rounded-lg 
                                @if($withdraw->status == 'processing') bg-yellow-500 text-white 
                                @elseif($withdraw->status == 'done') bg-green-500 text-white 
                                @elseif($withdraw->status == 'canceled') bg-red-500 text-white 
                                @endif">
                                {{ ucfirst($withdraw->status) }}
                            </span>
                        </td>
                        <td class="border border-gray-300 px-4 py-2">{{ $withdraw->created_at }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-gray-600">Tidak ada permintaan withdraw.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div id="withdrawModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center">
    <div class="bg-white p-6 rounded-lg shadow-lg w-96">
        <h2 class="text-xl font-semibold mb-4">Tarik Dana</h2>
        <form id="withdrawForm">
            <label class="block text-sm font-medium">Jumlah Withdraw</label>
            <input type="number" id="amount" name="amount" class="w-full border rounded p-2 mb-2" placeholder="Masukkan jumlah" required>

            <label class="block text-sm font-medium">Nomor Rekening</label>
            <input type="text" id="account_number" name="account_number" class="w-full border rounded p-2 mb-2" placeholder="Masukkan No Rekening" required>

            <label class="block text-sm font-medium">Pilih Bank</label>
            <select id="bank" name="bank" class="w-full border rounded p-2 mb-2">
                <option value="BCA">BCA</option>
                <option value="Mandiri">Mandiri</option>
                <option value="BNI">BNI</option>
                <option value="BRI">BRI</option>
            </select>

            <label class="block text-sm font-medium">Atas Nama</label>
            <input type="text" id="account_name" name="account_name" class="w-full border rounded p-2 mb-4" placeholder="Masukkan Nama Pemilik Rekening" required>
        </form>

        <div class="flex justify-end space-x-2">
            <button onclick="closeWithdrawModal()" class="bg-gray-500 text-white px-4 py-2 rounded">Batal</button>
            <button onclick="confirmWithdraw()" class="bg-blue-500 text-white px-4 py-2 rounded">Lanjutkan</button>
        </div>
    </div>
</div>

<!-- Konfirmasi Modal -->
<div id="confirmModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center">
    <div class="bg-white p-6 rounded-lg shadow-lg w-96 text-center">
        <h2 class="text-xl font-semibold mb-4">Konfirmasi Withdraw</h2>
        <p id="confirmText" class="mb-4 text-gray-700"></p>
        <div class="flex justify-center space-x-2">
            <button onclick="closeConfirmModal()" class="bg-gray-500 text-white px-4 py-2 rounded">Batal</button>
            <button onclick="submitWithdraw()" class="bg-green-500 text-white px-4 py-2 rounded">Ya, Tarik Dana</button>
        </div>
    </div>
</div>

<script>
function openWithdrawModal() {
    document.getElementById('withdrawModal').classList.remove('hidden');
}

function closeWithdrawModal() {
    document.getElementById('withdrawModal').classList.add('hidden');
}

function confirmWithdraw() {
    const amount = document.getElementById('amount').value;
    const bank = document.getElementById('bank').value;
    const accountNumber = document.getElementById('account_number').value;
    const accountName = document.getElementById('account_name').value;

    if (!amount || !accountNumber || !accountName) {
        alert("Mohon isi semua data dengan benar.");
        return;
    }

    document.getElementById('confirmText').innerText = `Anda akan menarik Rp ${parseInt(amount).toLocaleString('id-ID')} ke rekening ${bank} (${accountNumber}) atas nama ${accountName}. Apakah Anda yakin?`;
    document.getElementById('confirmModal').classList.remove('hidden');
    closeWithdrawModal();
}

function closeConfirmModal() {
    document.getElementById('confirmModal').classList.add('hidden');
}

function submitWithdraw() {
    const data = {
        amount: document.getElementById('amount').value,
        account_number: document.getElementById('account_number').value,
        bank: document.getElementById('bank').value,
        account_name: document.getElementById('account_name').value
    };

    fetch("{{ route('tutor.withdraw.process') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
        .then(result => {
        if (result.success) {
            Swal.fire({
                title: "Sukses!",
                text: "Withdraw berhasil diajukan.",
                icon: "success",
                confirmButtonText: "OK"
            }).then(() => location.reload());
        } else {
            Swal.fire({
                title: "Gagal!",
                text: result.message,
                icon: "error",
                confirmButtonText: "Coba Lagi"
            });
        }
    })
    .catch(error => {
        console.error(error);
        Swal.fire({
            title: "Error!",
            text: "Terjadi kesalahan, coba lagi.",
            icon: "error",
            confirmButtonText: "OK"
        });
    });


    closeConfirmModal();
}
</script>
@endsection