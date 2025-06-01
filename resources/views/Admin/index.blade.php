@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-white p-6">
    <h1 class="text-4xl font-extrabold text-gray-800 mb-10"> Dashboard Admin</h1>

    <!-- Statistik -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
    <!-- Total Users -->
    <div class="bg-white p-4 rounded-xl shadow border border-gray-100 hover:shadow-md transition duration-300">
        <p class="text-xs text-gray-500">Total Users</p>
        <h2 class="text-2xl font-semibold text-gray-800 mt-1">{{ $totalUsers - 1 }}</h2>
    </div>

    <!-- Total Uang Perusahaan -->
    <div class="bg-gradient-to-r from-green-100 to-green-200 p-4 rounded-xl shadow border border-green-300 hover:shadow-md transition duration-300">
        <p class="text-xs text-green-800">Total Uang Perusahaan</p>
        <h2 class="text-2xl font-semibold text-green-900 mt-1">Rp {{ number_format($totalBalance, 0, ',', '.') }}</h2>
    </div>

    <!-- Total Deposit -->
    <div class="bg-gradient-to-r from-blue-100 to-blue-200 p-4 rounded-xl shadow border border-blue-300 hover:shadow-md transition duration-300">
        <p class="text-xs text-blue-800">Total Deposit</p>
        <h2 class="text-2xl font-semibold text-blue-900 mt-1">Rp {{ number_format($totalDeposit, 0, ',', '.') }}</h2>
    </div>

    <!-- Total Withdraw -->
    <div class="bg-gradient-to-r from-red-100 to-red-200 p-4 rounded-xl shadow border border-red-300 hover:shadow-md transition duration-300">
        <p class="text-xs text-red-800">Total Withdraw</p>
        <h2 class="text-2xl font-semibold text-red-900 mt-1">Rp {{ number_format($totalWithdraw, 0, ',', '.') }}</h2>
    </div>

    <!-- Jumlah Tutor -->
    <div class="bg-gradient-to-r from-yellow-100 to-yellow-200 p-4 rounded-xl shadow border border-yellow-300 hover:shadow-md transition duration-300">
        <p class="text-xs text-yellow-800">Jumlah Tutor</p>
        <h2 class="text-2xl font-semibold text-yellow-900 mt-1">{{ $role1Count }}</h2>
    </div>
</div>


    <!-- Grafik -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100 hover:shadow-2xl transition duration-300">
            <canvas id="roleChart"></canvas>
            <p class="text-center mt-4 text-gray-700 font-semibold">User Berdasarkan Role</p>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-lg flex flex-col items-center justify-center hover:shadow-2xl transition duration-300">
            <canvas id="depositChart" class="max-w-full"></canvas>
            <p class="text-center text-blue-600 font-semibold mt-4">Grafik Deposit / Bulan</p>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-lg flex flex-col items-center justify-center hover:shadow-2xl transition duration-300">
            <canvas id="withdrawChart"></canvas>
            <p class="text-center mt-4 text-red-500 font-semibold">Grafik Withdraw / Bulan</p>
        </div>
    </div>

    <!-- Grafik Transaksi Confirmed -->
    <div class="bg-white rounded-2xl p-8 shadow-lg border border-gray-100 mt-12 hover:shadow-2xl transition duration-300">
        <form method="GET" class="mb-6 flex flex-wrap items-center gap-4">
            <label for="month" class="font-semibold text-gray-700"> Pilih Bulan:</label>
            <select name="month" id="month" onchange="this.form.submit()" class="select select-bordered w-full max-w-xs border-gray-300 rounded-xl shadow-sm">
                <option value="">Semua Bulan</option>
                @foreach ($availableMonths as $month)
                    <option value="{{ $month }}" {{ $selectedMonth == $month ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::createFromFormat('Y-m', $month)->translatedFormat('F Y') }}
                    </option>
                @endforeach
            </select>
        </form>

        <canvas id="transactionChart" class="mb-6"></canvas>
        <h3 class="text-center text-2xl font-bold text-green-600">Grafik Transaksi Berhasil</h3>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

<script>
    const transactionChart = new Chart(document.getElementById('transactionChart'), {
        type: 'line',
        data: {
            labels: {!! json_encode($transactionDates) !!},
            datasets: [{
                label: 'Total Transaksi',
                data: {!! json_encode($transactionTotals) !!},
                borderColor: '#10b981',
                backgroundColor: 'rgba(16, 185, 129, 0.2)',
                fill: true,
                tension: 0.4,
                pointRadius: 5,
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: value => 'Rp ' + value.toLocaleString(),
                        color: '#065f46'
                    }
                },
                x: {
                    ticks: {
                        color: '#4b5563'
                    }
                }
            },
            plugins: {
                legend: { display: false },
                tooltip: { mode: 'index', intersect: false }
            }
        }
    });

    const roleChart = new Chart(document.getElementById('roleChart'), {
    type: 'pie',
    data: {
        labels: ['Tutor', 'Pelajar'],
        datasets: [{
            data: [{{ $role1Count }}, {{ $role2Count }}],
            backgroundColor: ['#60a5fa', '#facc15'],
            borderColor: '#fff',
            borderWidth: 2
        }]
    },
    options: {
        plugins: {
            legend: { position: 'bottom' },
            datalabels: {
                color: '#111',
                formatter: (value, ctx) => {
                    let sum = ctx.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                    let percentage = ((value / sum) * 100).toFixed(1) + "%";
                    return percentage;
                },
                font: {
                    weight: 'bold',
                    size: 14
                }
            }
        }
    },
    plugins: [ChartDataLabels]
});


    const depositChart = new Chart(document.getElementById('depositChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($depositMonths) !!},
            datasets: [{
                label: 'Deposit',
                data: {!! json_encode($depositTotals) !!},
                backgroundColor: '#3b82f6',
                borderRadius: 6
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: value => 'Rp ' + value.toLocaleString()
                    }
                }
            },
            plugins: {
                legend: { display: false }
            }
        }
    });

    const withdrawChart = new Chart(document.getElementById('withdrawChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($withdrawMonths) !!},
            datasets: [{
                label: 'Withdraw',
                data: {!! json_encode($withdrawTotals) !!},
                backgroundColor: '#ef4444',
                borderRadius: 6
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: value => 'Rp ' + value.toLocaleString()
                    }
                }
            },
            plugins: {
                legend: { display: false }
            }
        }
    });
</script>
@endsection
