@extends('layouts.tutorPanel')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-white p-6">
    <h1 class="text-4xl font-extrabold text-gray-800 mb-10">Dashboard Tutor</h1>

    <!-- Statistik -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Saldo Wallet -->
        <div class="bg-white p-4 rounded-xl shadow border border-gray-100 hover:shadow-md transition duration-300">
            <p class="text-xs text-gray-500">Saldo Wallet</p>
            <h2 class="text-4xl font-semibold text-gray-800 mt-1">Rp {{ number_format($walletBalance, 0, ',', '.') }}</h2>
        </div>

        <!-- Total Pendapatan Per Bulan -->
        <div class="bg-gradient-to-r from-green-100 to-green-200 p-6 rounded-xl shadow border border-green-300 hover:shadow-md transition duration-300">
            <p class="text-xs text-gray-500">Total Pendapatan Per Bulan</p>
            <h2 class="text-4xl font-semibold text-gray-800 mt-1">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h2>
        </div>

        <!-- Total Withdraw -->
        <div class="bg-gradient-to-r from-red-100 to-red-200 p-6 rounded-xl shadow border border-red-300 hover:shadow-md transition duration-300">
            <p class="text-xs text-red-800">Total Withdraw</p>
            <h2 class="text-4xl font-bold text-red-900 mt-1">Rp {{ number_format($totalWithdraw, 0, ',', '.') }}</h2>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white p-4 rounded-lg shadow">
            <h2 class="text-xl font-semibold mb-4">Jumlah Transaksi per Bulan</h2>
            <canvas id="transactionsChart" height="200"></canvas>
        </div>

        <div class="bg-white p-4 rounded-lg shadow">
            <h2 class="text-xl font-semibold mb-4">Pendapatan per Bulan (Rp)</h2>
            <canvas id="revenueChart" height="200"></canvas>
        </div>
    </div>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const months = @json($months);
    const transactionCounts = @json($transactionCounts);
    const monthlyRevenue = @json($monthlyRevenue);

    // Chart Jumlah Transaksi
    new Chart(document.getElementById('transactionsChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: months,
            datasets: [{
                label: 'Jumlah Transaksi',
                data: transactionCounts,
                backgroundColor: 'rgba(59, 130, 246, 0.7)', // biru DaisyUI btn-primary
                borderColor: 'rgba(59, 130, 246, 1)',
                borderWidth: 1,
                borderRadius: 4
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    stepSize: 1
                }
            }
        }
    });

    // Chart Pendapatan
    new Chart(document.getElementById('revenueChart').getContext('2d'), {
        type: 'line',
        data: {
            labels: months,
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: monthlyRevenue,
                fill: true,
                backgroundColor: 'rgba(34, 197, 94, 0.3)', // hijau muda
                borderColor: 'rgba(34, 197, 94, 1)', // hijau DaisyUI btn-success
                borderWidth: 3,
                tension: 0.3
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        // Format angka ke Rp
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });
</script>
@endsection
