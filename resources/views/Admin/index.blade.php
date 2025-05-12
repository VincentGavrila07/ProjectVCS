@extends('layouts.admin') <!-- Menggunakan layout admin -->

@section('content') <!-- Mengisi konten untuk menu 1 -->
    <h2 class="text-2xl font-semibold mb-4">Dashboard Admin</h2>

    <!-- Grafik User Yang Terdaftar -->
    <div class="grafik mb-8">
        <!-- Grid layout untuk grafik -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Grafik pertama -->
            <div class="max-w-full mx-auto">
                <canvas id="roleChart"></canvas>
                <!-- Nama grafik di bawah -->
                <h2 class="text-center text-lg font-semibold mt-4 text-gray-700">Grafik User Berdasarkan Role</h2>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

    <script>
        // Data untuk Pie Chart
        var role1Count = {{ $role1Count }};
        var role2Count = {{ $role2Count }};
        var totalUsers = role1Count + role2Count;

        var ctx = document.getElementById('roleChart').getContext('2d');
        var roleChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Tutor', 'Pelajar'], // Label untuk chart
                datasets: [{
                    label: 'Persentase User Berdasarkan Role',
                    data: [role1Count, role2Count], // Data jumlah pengguna berdasarkan role
                    backgroundColor: ['#3498db', '#f39c12'], // Warna biru dan kuning
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    datalabels: {
                        color: '#fff', // Warna teks label
                        font: {
                            weight: 'bold',
                            size: 16
                        },
                        formatter: function(value, context) {
                            var percentage = ((value / totalUsers) * 100).toFixed(2) + '%'; // Hitung persentase
                            var data = value + ' user\n' + percentage; // Tampilkan jumlah dan persentase
                            return data; // Menampilkan jumlah dan persentase di dalam chart
                        },
                        align: 'center', // Menempatkan label di tengah
                        anchor: 'center', // Menempatkan label di tengah
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                // Menampilkan jumlah user dan persen pada tooltip
                                var value = tooltipItem.raw;
                                var percentage = ((value / totalUsers) * 100).toFixed(2) + '%';
                                return tooltipItem.label + ': ' + value + ' user (' + percentage + ')';
                            }
                        }
                    }
                }
            },
            plugins: [ChartDataLabels] // Mengaktifkan plugin datalabels
        });
    </script>
@endsection
