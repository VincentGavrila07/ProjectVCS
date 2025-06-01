@extends(session('role') == 2 ? 'layouts.pelajarPanel' : 'layouts.tutorPanel')

@section('content')
<div class="container mx-auto p-6">
    <div class="max-w-lg mx-auto bg-white rounded-lg shadow-lg p-6">
        
       <div class="bg-white rounded-xl shadow-lg p-8 space-y-6">
            <h2 class="text-4xl font-extrabold text-center text-blue-700 mb-4">Deposit Saldo</h2>

            <div class="flex flex-col items-center bg-gradient-to-r from-blue-600 to-blue-500 text-white rounded-lg p-6 shadow-md animate-fadeIn">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c1.657 0 3 .895 3 2s-1.343 2-3 2-3-.895-3-2 1.343-2 3-2z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 12v4m0 0a3 3 0 003 3h4m-7-7H5a3 3 0 00-3 3v1m7-7v-1m0 1v-1a3 3 0 00-3-3H5m0 0a3 3 0 013-3h4" />
                </svg>
                <p class="text-lg font-semibold">Saldo Anda</p>
                <p id="saldo" class="text-3xl font-extrabold mt-1">Rp {{ number_format($wallet->balance, 0, ',', '.') }}</p>
            </div>

            <form id="deposit-form" class="space-y-5" onsubmit="return false;">
                <div>
                    <label for="amount" class="block text-gray-700 font-semibold mb-1">Jumlah Deposit</label>
                    <div class="relative">
                        <input
                            type="number"
                            id="amount"
                            name="amount"
                            min="10000"
                            placeholder="Minimal Rp 10.000"
                            class="w-full px-5 py-3 border border-gray-300 rounded-xl shadow-sm text-lg font-semibold focus:outline-none focus:ring-4 focus:ring-blue-400 focus:border-blue-600"
                            oninput="syncSlider(this.value)"
                        />
                        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 font-semibold">Rp</span>
                    </div>
                </div>

                <input
                    type="range"
                    id="amountRange"
                    min="10000"
                    max="1000000"
                    step="5000"
                    value="10000"
                    class="w-full accent-blue-600"
                    onchange="syncInput(this.value)"
                />

                <button
                    type="button"
                    onclick="deposit()"
                    class="w-full py-4 bg-blue-600 hover:bg-blue-700 transition rounded-xl text-white font-extrabold text-xl shadow-lg focus:outline-none focus:ring-4 focus:ring-blue-400 focus:ring-offset-2"
                >
                    Deposit Sekarang
                </button>
            </form>
        </div>
        <div class="text-sm text-gray-600 mt-2 text-center">
            Minimal deposit: <span class="font-semibold text-blue-700">Rp 10.000</span>, maksimal: <span class="font-semibold text-blue-700">Rp 1.000.000</span>.
        </div>
    </div>

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
        function syncSlider(val) {
        const slider = document.getElementById('amountRange');
        slider.value = val < 10000 ? 10000 : val;
    }

    function syncInput(val) {
        document.getElementById('amount').value = val;
    }

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

        if (amount > 1000000) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Maksimal deposit adalah Rp 1.000.000',
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