<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Http\Request;

class PelajarController extends Controller
{
 public function dashboard(Request $request)
{
    $studentId = session('id');

    // Ambil semua transaksi yang sudah dikonfirmasi beserta relasi subject
    $transactions = Transaction::with('subject')
        ->where('student_id', $studentId)
        ->where('status', 'confirmed')
        ->get();

    // === Chart Transaksi Berhasil per Bulan ===
    $monthlyGrouped = $transactions->groupBy(function ($item) {
        return $item->created_at->format('Y-m');
    });

    $months = [];
    $monthlySuccessTransactions = [];

    for ($i = 5; $i >= 0; $i--) {
        $monthKey = Carbon::now()->subMonths($i)->format('Y-m');
        $months[] = Carbon::now()->subMonths($i)->format('M Y');

        $count = isset($monthlyGrouped[$monthKey]) ? $monthlyGrouped[$monthKey]->count() : 0;
        $monthlySuccessTransactions[] = $count;
    }

    // === Filter dan Ringkasan Subject per Bulan ===
    $selectedMonth = $request->query('month', Carbon::now()->format('Y-m'));

    $subjectSummary = $transactions
        ->filter(function ($tx) use ($selectedMonth) {
            return $tx->created_at->format('Y-m') === $selectedMonth;
        })
        ->groupBy(function ($tx) {
            return optional($tx->subject)->subjectName ?? 'Tanpa Nama Subjek';
        })
        ->map->count();

    $subjectLabels = $subjectSummary->keys();
    $subjectCounts = $subjectSummary->values();

    // === Ambil Saldo Wallet ===
    $wallet = Wallet::where('user_id', $studentId)->first();
    $walletBalance = $wallet ? $wallet->balance : 0;

    // === Hitung Subject Terbanyak Dipelajari (Rekomendasi) ===
    $groupedSubjects = $transactions
        ->filter(fn($tx) => $tx->subject)
        ->groupBy(fn($tx) => $tx->subject->subjectName)
        ->map->count();

    $mostFrequentSubject = $groupedSubjects->sortDesc()->keys()->first();
    $mostFrequentSubjectCount = $mostFrequentSubject ? $groupedSubjects[$mostFrequentSubject] : 0;

    // === Kirim ke View ===
    return view('mainpage.pelajar.index', compact(
        'months',
        'monthlySuccessTransactions',
        'walletBalance',
        'subjectLabels',
        'subjectCounts',
        'selectedMonth',
        'mostFrequentSubject',
        'mostFrequentSubjectCount'
    ));
}


}
