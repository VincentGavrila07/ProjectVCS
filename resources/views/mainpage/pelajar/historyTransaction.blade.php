@extends('layouts.pelajarPanel')

@section('content')
    <h2 class="text-2xl font-semibold mb-4">Daftar Transaksi</h2>

    <!-- Panel Pencarian -->
    <form method="GET" action="{{ route('pelajar.transaksiList') }}" class="mb-4">
        <div class="flex items-center">
            <input type="text" name="search" value="{{ request()->input('search') }}" placeholder="Cari berdasarkan  id, StudentID, Tutor ID" class="border px-4 py-2 rounded-lg mr-2 w-96">
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
                <th class="border border-gray-300 px-4 py-2">Tutor Name</th>
                <th class="border border-gray-300 px-4 py-2">Link Zoom</th>
                <th class="border border-gray-300 px-4 py-2">Amount</th>
                <th class="border border-gray-300 px-4 py-2">Status VCS</th>
                <th class="border border-gray-300 px-4 py-2">Dibuat</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($transactions as $transaction)
                <tr class="bg-gray-100">
                    <td class="border border-gray-300 px-4 py-2">{{ $transaction->id }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $transaction->tutor_name}}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $transaction->meeting_url ?? 'Tidak Ada Meeting Url' }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ number_format($transaction->amount, 0, ',', '.') }}</td>
                    <td class="border border-gray-300 px-4 py-2">
                        @php
                            $createdTime = strtotime($transaction->created_at);
                            $currentTime = time();
                            if ($transaction->status === 'canceled' || $transaction->status == 'pending') {
                                $statusVCS = 'Canceled';
                            } else {
                                $statusVCS = ($currentTime - $createdTime) <= (65 * 60) ? 'On Going' : 'Done';
                            }
                        @endphp
                        {{ $statusVCS }}
                    </td>
                    <td class="border border-gray-300 px-4 py-2">
                        {{ date('Y-m-d H:i', strtotime($transaction->created_at)) }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center py-4 text-gray-600">Tidak ada transaksi.</td>
                </tr>
            @endforelse
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

    td{
        font-size: 14px;
    }
</style>