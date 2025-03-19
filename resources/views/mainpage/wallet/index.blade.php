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
</script>
@endsection