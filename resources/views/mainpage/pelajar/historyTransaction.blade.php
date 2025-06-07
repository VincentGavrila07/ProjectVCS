@extends('layouts.pelajarPanel')

@section('content')
    <h2 class="text-2xl font-semibold mb-4">Daftar Transaksi</h2>

    <!-- Panel Pencarian -->
    <form method="GET" action="{{ route('pelajar.transaksiList') }}" class="mb-4">
        <div class="flex items-center">
            <input type="text" name="search" value="{{ request()->input('search') }}" placeholder="Cari berdasarkan  id, StudentID, Tutor ID" class="border px-4 py-2 rounded-lg mr-2 w-96">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </form>

    <!-- Tabel Data Transactions -->
    <table class="table-auto border-collapse border border-gray-400 w-full">
        <thead>
            <tr class="bg-gray-800 text-white">
                <th class="border border-gray-300 px-4 py-2">ID</th>
                <th class="border border-gray-300 px-4 py-2">Tutor Name</th>
                <th class="border border-gray-300 px-4 py-2">Subject Name</th>
                <th class="border border-gray-300 px-4 py-2">Link Zoom</th>
                <th class="border border-gray-300 px-4 py-2">Amount</th>
                <th class="border border-gray-300 px-4 py-2">Status VCS</th>
                <th class="border border-gray-300 px-4 py-2">Dibuat</th>
                <th class="border border-gray-300 px-4 py-2">Rating</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($transactions as $transaction)
                <tr class="bg-gray-100">
                    <td class="border border-gray-300 px-4 py-2">{{ $transaction->id }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $transaction->tutor_name}}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $transaction->subject_name }}</td>
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
                    <td class="border border-gray-300 px-4 py-2">
                        @if(!is_null($transaction->rating))
                            @for ($i = 1; $i <= 5; $i++)
                                <span class="{{ $i <= $transaction->rating ? 'text-yellow-500' : 'text-gray-400' }}">★</span>
                            @endfor
                        @else
                            <span class="text-gray-600">-</span>
                        @endif
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
        {{ $transactions->links() }}
    </div>

<!-- Tabel Transaksi Belum Diberi Rating -->
<h2 class="text-2xl font-semibold mt-6 mb-4">Transaksi Belum Diberi Rating</h2>
<table class="table-auto border-collapse border border-gray-400 w-full mb-6">
    <thead>
        <tr class="bg-gray-800 text-white">
            <th class="border border-gray-300 px-4 py-2">ID</th>
            <th class="border border-gray-300 px-4 py-2">Tutor Name</th>
            <th class="border border-gray-300 px-4 py-2">Subject Name</th>
            <th class="border border-gray-300 px-4 py-2">Dibuat</th>
            <th class="border border-gray-300 px-4 py-2">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @if($unratedTransactions->isEmpty())
            <tr>
                <td colspan="4" class="text-center py-4">Tidak ada yang belum di rating</td>
            </tr>
        @else
            @foreach ($unratedTransactions as $transaction)
                <tr class="bg-gray-100">
                    <td class="border border-gray-300 px-4 py-2">{{ $transaction->id }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $transaction->tutor_name }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $transaction->subject_name }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ date('Y-m-d H:i', strtotime($transaction->created_at)) }}</td>
                    <td class="border border-gray-300 px-4 py-2">
                        <form method="POST" action="{{ route('submitRating', $transaction->id) }}" id="rating-form-{{ $transaction->id }}">
                            @csrf
                            <input type="hidden" name="rating" id="rating-input-{{ $transaction->id }}" value="">

                            <div class="star-rating" data-transaction-id="{{ $transaction->id }}">
                                @for ($i = 1; $i <= 5; $i++)
                                    <span 
                                        onclick="submitRating({{ $transaction->id }}, {{ $i }})"
                                        onmouseover="highlightStars({{ $i }}, {{ $transaction->id }})"
                                        onmouseout="removeHighlight({{ $transaction->id }})"
                                        class="text-lg cursor-pointer text-gray-400">★</span>
                                @endfor
                            </div>
                        </form>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>

<!-- Pagination for Unrated Transactions -->
<div class="mt-4">
    {{ $unratedTransactions->appends(request()->except('unrated_page'))->links() }}
</div>

<script>
    function submitRating(transactionId, rating) {
        const form = document.getElementById(`rating-form-${transactionId}`);
        const ratingInput = document.getElementById(`rating-input-${transactionId}`);

        // Set the rating value
        ratingInput.value = rating;

        // Update styling for selected stars
        const stars = document.querySelectorAll(`#rating-form-${transactionId} .star-rating span`);
        stars.forEach((star, index) => {
            star.classList.toggle('selected', index < rating);
        });

        // Submit the form
        form.submit();
    }

    function highlightStars(star, transactionId) {
        const stars = document.querySelectorAll(`#rating-form-${transactionId} .star-rating span`);
        stars.forEach((starElement, index) => {
            starElement.classList.toggle('hover', index < star);
        });
    }

    function removeHighlight(transactionId) {
        const stars = document.querySelectorAll(`#rating-form-${transactionId} .star-rating span`);
        stars.forEach(star => {
            star.classList.remove('hover');
        });
    }
</script>


@endsection

<style>
    th.button {
        cursor: pointer;
    }

    th {
        background-color:#2d3748;
        color: #fff;
    }

    td{
        font-size: 14px;
    }
    .star-rating span {
    color: #d1d5db; /* Default gray color */
}
    .star-rating span {
        color: #d1d5db; /* Default gray color */
        transition: color 0.3s;
    }

    .star-rating span.hover,
    .star-rating span.selected {
        color: #fbbf24; /* Yellow color */
    }


</style>
