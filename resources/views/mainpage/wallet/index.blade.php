@extends(session('role') == 2 ? 'layouts.pelajarPanel' : 'layouts.tutorPanel')

@section('content')
<div class="container mx-auto p-6">
    <div class="max-w-lg mx-auto bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-3xl font-bold text-center text-gray-800 mb-6">Deposit</h2>
        
        <!-- Saldo Section -->
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-6 text-white mb-6">
            <p class="text-sm font-semibold">Saldo {{ session('username') }} :</p>
            <p class="text-2xl font-bold">Rp {{ number_format($wallet->balance, 0, ',', '.') }}</p>
        </div>

        <!-- Deposit Form -->
        <form id="deposit-form" class="space-y-4">
            <div>
                <label for="amount" class="block text-sm font-medium text-gray-700">Jumlah Deposit</label>
                <input type="number" id="amount" name="amount" min="10000" placeholder="Minimal Rp 10.000"
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
            <button type="button" onclick="deposit()"
                class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                Deposit
            </button>
        </form>
    </div>

         <!-- Tabel Data Deposit -->
    <!-- Tabel Data Deposit -->
    <div class="mt-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">History Deposit</h2>
        <table class="table-auto border-collapse border border-gray-400 w-full mt-4">
            <thead>
                <tr class="bg-gray-800 text-white">
                    <th class="border border-gray-300 px-4 py-2 text-left">ID</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Order ID</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Jumlah</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Status</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Dibuat</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($transactions as $transaction)
                    <tr class="bg-gray-100">
                        <td class="border border-gray-300 px-4 py-2">{{ $transaction->id }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $transaction->order_id }}</td>
                        <td class="border border-gray-300 px-4 py-2">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</td>
                        <td class="border border-gray-300 px-4 py-2 text-center">
                            <span class="px-3 py-1 rounded-lg 
                                @if($transaction->status == 'settlement') bg-green-500 text-white 
                                @elseif($transaction->status == 'pending') bg-yellow-500 text-white 
                                @elseif($transaction->status == 'failed') bg-red-500 text-white 
                                @endif">
                                {{ ucfirst($transaction->status) }}
                            </span>
                        </td>
                        <td class="border border-gray-300 px-4 py-2">{{ $transaction->created_at->format('Y-m-d H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-gray-600">Tidak ada transaksi deposit.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function deposit() {
        const amount = document.getElementById('amount').value;

        // Validasi minimal deposit
        if (amount < 10000) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Minimal deposit adalah Rp 10.000',
            });
            return;
        }

        // Kirim data deposit ke backend
        fetch('/wallet/deposit', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ amount: amount })
        })
        .then(response => response.json())
        .then(data => {
            if (data.snap_token) {
                // Tampilkan popup pembayaran Midtrans
                snap.pay(data.snap_token, {
                    onSuccess: function(result) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Pembayaran Berhasil!',
                            text: 'Saldo Anda akan segera ditambahkan.',
                        }).then(() => {
                            window.location.reload(); // Reload halaman untuk update saldo
                        });
                    },
                    onPending: function(result) {
                        Swal.fire({
                            icon: 'info',
                            title: 'Menunggu Pembayaran...',
                            text: 'Silakan selesaikan pembayaran Anda.',
                        });
                    },
                    onError: function(result) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Pembayaran Gagal',
                            text: 'Terjadi kesalahan saat memproses pembayaran.',
                        });
                    }
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Terjadi kesalahan saat memproses deposit.',
                });
            }
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Terjadi kesalahan saat menghubungi server.',
            });
        });
    }
fetch('/wallet/deposit/data')
    .then(response => response.json())
    .then(data => {
        const tbody = document.querySelector('table tbody');
        tbody.innerHTML = ''; // Clear the existing rows

        if (data.length === 0) {
            tbody.innerHTML = '<tr><td colspan="5" class="text-center py-4 text-gray-600">Tidak ada transaksi deposit.</td></tr>';
        } else {
            data.forEach(transaction => {
                const row = document.createElement('tr');
                row.classList.add('bg-gray-100');

                row.innerHTML = `
                    <td class="border border-gray-300 px-4 py-2">${transaction.id}</td>
                    <td class="border border-gray-300 px-4 py-2">${transaction.order_id}</td>
                    <td class="border border-gray-300 px-4 py-2">Rp ${transaction.amount.toLocaleString()}</td>
                    <td class="border border-gray-300 px-4 py-2">
                        ${transaction.status === 'settlement' ? '<span class="text-green-500 font-bold">Sukses</span>' : (transaction.status === 'pending' ? '<span class="text-yellow-500 font-bold">Pending</span>' : '<span class="text-red-500 font-bold">Gagal</span>')}
                    </td>
                    <td class="border border-gray-300 px-4 py-2">${new Date(transaction.created_at).toLocaleString()}</td>
                `;
                tbody.appendChild(row);
            });
        }
    })
    .catch(error => {
        console.error("Error fetching deposit data:", error);
    });



</script>
@endsection