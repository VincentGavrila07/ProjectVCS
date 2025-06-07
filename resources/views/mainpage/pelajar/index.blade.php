@extends('layouts.pelajarPanel')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-white p-6">
    <h1 class="text-4xl font-extrabold text-gray-800 mb-10">Dashboard Pelajar</h1>

    <!-- Kotak Statistik -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">

        <!-- Saldo Wallet -->
        <div class="bg-gradient-to-r from-green-100 to-green-200 p-6 rounded-xl shadow border border-green-300 hover:shadow-md transition duration-300">
            <p class="text-xs text-gray-500">Saldo Wallet</p>
            <h2 class="text-4xl font-semibold text-gray-800 mt-1">Rp {{ number_format($walletBalance, 0, ',', '.') }}</h2>
        </div>
        <div class="bg-gradient-to-r from-blue-100 to-blue-200 p-6 rounded-xl shadow border border-blue-300 hover:shadow-md transition duration-300">
            <p class="text-xs text-gray-500">Rekomendasi Subject</p>
            <h2 class="text-4xl font-semibold text-gray-800 mt-1">{{ $mostFrequentSubject ?? 'Belum Ada Data' }} ({{$mostFrequentSubjectCount}} Transaksi)</h2>
            <p class="mt-2 text-sm text-gray-600 italic">*Notes: Rekomendasi subject diambil dari riwayat transaksi dengan subject yang dipelajari terbanyak.</p>
        </div>

    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Grafik Transaksi Berhasil -->
        <div class="bg-white p-6 rounded-xl shadow border border-gray-100">
            <h2 class="text-xl font-semibold mb-4">Transaksi Berhasil per Bulan</h2>
            <canvas id="transactionChart" height="200"></canvas>
        </div>
        <!-- Chart Subject dengan Dropdown Filter Bulan -->
        <div class="bg-white p-6 rounded-xl shadow border border-gray-100">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold">Subject Per Bulan</h2>
                <form method="GET" action="{{ route('pelajar') }}">
                    <label for="month" class="text-sm text-gray-600">Pilih Bulan:</label>
                    <select name="month" id="month" onchange="this.form.submit()" class="ml-2 px-2 py-1 rounded border-gray-300">
                        @for ($i = 5; $i >= 0; $i--)
                            @php
                                $value = \Carbon\Carbon::now()->subMonths($i)->format('Y-m');
                                $label = \Carbon\Carbon::now()->subMonths($i)->translatedFormat('F Y');
                            @endphp
                            <option value="{{ $value }}" {{ $selectedMonth === $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endfor
                    </select>
                </form>
            </div>
            <div id="subjectChartContainer">
                <canvas id="subjectChart" height="200"></canvas>
            </div>
            <p id="noSubjectData" class="text-center text-gray-500 hidden h-[200px] flex justify-center items-center">Belum ada transaksi di bulan ini</p>
        </div>
    </div>

</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const months = @json($months);
    const transactions = @json($monthlySuccessTransactions);

    new Chart(document.getElementById('transactionChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: months,
            datasets: [{
                label: 'Transaksi Berhasil',
                data: transactions,
                backgroundColor: 'rgba(34, 197, 94, 0.7)', // hijau
                borderColor: 'rgba(34, 197, 94, 1)',
                borderWidth: 1,
                borderRadius: 4
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });

    const subjectLabels = @json($subjectLabels);
    const subjectCounts = @json($subjectCounts);

    // Cek apakah datanya kosong
    const hasSubjectData = subjectCounts.length > 0 && subjectCounts.some(count => count > 0);

    if (hasSubjectData) {
        document.getElementById('subjectChart').classList.remove('hidden');
        document.getElementById('noSubjectData').classList.add('hidden');

        new Chart(document.getElementById('subjectChart').getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: subjectLabels,
                datasets: [{
                    label: 'Jumlah',
                    data: subjectCounts,
                    backgroundColor: [
                        'rgba(59, 130, 246, 0.6)',
                        'rgba(34, 197, 94, 0.6)',
                        'rgba(234, 179, 8, 0.6)',
                        'rgba(239, 68, 68, 0.6)',
                        'rgba(99, 102, 241, 0.6)'
                    ],
                    borderColor: 'white',
                    borderWidth: 2
                }]
            },
            options: {
                plugins: {
                    legend: {
                        position: 'right'
                    }
                }
            }
        });
    } else {
        // Sembunyikan canvas, tampilkan pesan
        document.getElementById('subjectChartContainer').classList.add('hidden');
        document.getElementById('noSubjectData').classList.remove('hidden');
    }

</script>
@endsection
