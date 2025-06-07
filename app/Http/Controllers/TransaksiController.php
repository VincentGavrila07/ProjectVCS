<?php

namespace App\Http\Controllers;
use App\Models\Transaction;
use App\Models\MsUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    public function transaksiList(Request $request)
    {
        $query = Transaction::with(['student', 'tutor']);

        if ($request->has('search')) {
            $search = $request->input('search');

            $query->where(function ($q) use ($search) {
                $q->where('id', 'LIKE', "%{$search}%")
                ->orWhereHas('student', function ($qs) use ($search) {
                    $qs->where('email', 'LIKE', "%{$search}%");
                })
                ->orWhereHas('tutor', function ($qt) use ($search) {
                    $qt->where('email', 'LIKE', "%{$search}%");
                });
            });
        }

        $transactions = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.transaksi', compact('transactions'));
    }


    public function destroyTransaction($id)
    {
        $transaction = Transaction::find($id);
        
        if (!$transaction) {
            return response()->json(['success' => false, 'message' => 'Transaksi tidak ditemukan.'], 404);
        }

        // Hapus semua data terkait di roomzoomcall sebelum menghapus transaksi
        DB::table('roomzoomcall')->where('transaction_id', $id)->delete();

        // Hapus transaksi setelah data terkait dihapus
        $transaction->delete();

        return response()->json(['success' => true, 'message' => 'Transaksi berhasil dihapus.']);
    }

    public function historyTransaksi(Request $request)
    {
        $userId = session('id'); 
        $role = session('role'); 

        if ($role == 1) {
            $query = DB::table('transactions')
                ->where('transactions.tutor_id', $userId);
            $view = 'mainpage.tutor.historyTransaction';
        } elseif ($role == 2) {
            $query = DB::table('transactions')
                ->where('transactions.student_id', $userId);
            $view = 'mainpage.pelajar.historyTransaction';
        } else {
            return abort(403, 'Akses tidak diizinkan'); 
        }

        // Filter Pencarian untuk Transaksi Utama
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('transactions.id', 'LIKE', "%{$search}%")
                ->orWhere('transactions.student_id', 'LIKE', "%{$search}%")
                ->orWhere('transactions.tutor_id', 'LIKE', "%{$search}%");
            });
        }

        // Query Transaksi Utama dengan Pagination
        $transactions = $query
            ->leftJoin('roomzoomcall', 'transactions.id', '=', 'roomzoomcall.transaction_id')
            ->leftJoin('msuser as student', 'transactions.student_id', '=', 'student.id')
            ->leftJoin('msuser as tutor', 'transactions.tutor_id', '=', 'tutor.id')
            ->leftJoin('mssubject', 'transactions.subject_id', '=', 'mssubject.id')
            ->select(
                'transactions.*', 
                'roomzoomcall.meeting_url',
                'student.username as student_name',
                'tutor.username as tutor_name',
                'mssubject.subjectName as subject_name'
            )
            ->orderBy('transactions.created_at', 'desc')
            ->paginate(10);
            
            // Query Transaksi Belum Diberi Rating dengan Pagination
            $unratedTransactions = DB::table('transactions')
            ->leftJoin('mssubject', 'transactions.subject_id', '=', 'mssubject.id')
            ->where('transactions.status', 'confirmed')
            ->whereNull('transactions.rating')
            ->where(function ($q) use ($userId, $role) {
                if ($role == 1) {
                    $q->where('transactions.tutor_id', $userId);
                } elseif ($role == 2) {
                    $q->where('transactions.student_id', $userId);
                }
            })
            ->leftJoin('msuser as tutor', 'transactions.tutor_id', '=', 'tutor.id')
            ->select(
                'transactions.id',
                'transactions.created_at',
                'tutor.username as tutor_name',
                'mssubject.subjectName as subject_name'
            )
            ->orderBy('transactions.created_at', 'desc')
            ->paginate(5, ['*'], 'unrated_page'); // Gunakan page query parameter yang berbeda

        return view($view, compact('transactions', 'unratedTransactions'));
    }

    public function giveRating(Request $request, $transactionId)
    {
        $transaction = Transaction::find($transactionId);

        if (!$transaction || $transaction->status !== 'DONE') {
            return redirect()->back()->with('error', 'Transaksi tidak valid untuk diberikan rating.');
        }

        return view('ratingForm', compact('transaction'));
    }

    public function submitRating(Request $request, $transactionId)
    {
        $request->validate([
            'rating' => 'required|numeric|min:1|max:5',
        ]);

        $transaction = Transaction::find($transactionId);

        if (!$transaction || $transaction->status !== 'confirmed') {
            return redirect()->back()->with('error', 'Transaksi tidak valid untuk diberikan rating.');
        }

        $transaction->rating = $request->input('rating');
        $transaction->save();

        $tutor = MsUser::find($transaction->tutor_id);
        $tutor->Rating = $tutor->calculateAverageRating();
        $tutor->save();

        return redirect()->back()->with('success', 'Rating berhasil diberikan.');
    }


    
}
