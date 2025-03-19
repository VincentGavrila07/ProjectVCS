<?php

namespace App\Http\Controllers;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    public function transaksiList(Request $request)
    {
        $query = Transaction::query();

        // Cek apakah ada pencarian berdasarkan student_id atau tutor_id
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('id', 'LIKE', "%{$search}%")
                ->orwhere('student_id', 'LIKE', "%{$search}%")
                ->orWhere('tutor_id', 'LIKE', "%{$search}%");
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
        // Ambil ID dan role pengguna dari session
        $userId = session('id'); 
        $role = session('role'); 
    
        // Pastikan hanya role 1 (Tutor) dan role 2 (Pelajar) yang bisa mengakses
        if ($role == 1) {
            // Jika Tutor (role 1), filter berdasarkan tutor_id
            $query = DB::table('transactions')
                ->where('transactions.tutor_id', $userId);
            $view = 'mainpage.tutor.historyTransaction';
        } elseif ($role == 2) {
            // Jika Pelajar (role 2), filter berdasarkan student_id
            $query = DB::table('transactions')
                ->where('transactions.student_id', $userId);
            $view = 'mainpage.pelajar.historyTransaction';
        } else {
            // Jika bukan role 1 atau 2, kembalikan dengan abort atau redirect ke dashboard
            return abort(403, 'Akses tidak diizinkan'); 
        }
    
        // Cek apakah ada pencarian berdasarkan id atau lawan transaksi
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('transactions.id', 'LIKE', "%{$search}%")
                  ->orWhere('transactions.student_id', 'LIKE', "%{$search}%")
                  ->orWhere('transactions.tutor_id', 'LIKE', "%{$search}%");
            });
        }
    
        // Join dengan tabel msuser untuk mendapatkan student_name dan tutor_name
        $transactions = $query
            ->leftJoin('roomzoomcall', 'transactions.id', '=', 'roomzoomcall.transaction_id')
            ->leftJoin('msuser as student', 'transactions.student_id', '=', 'student.id') // Join untuk student
            ->leftJoin('msuser as tutor', 'transactions.tutor_id', '=', 'tutor.id') // Join untuk tutor
            ->select(
                'transactions.*', 
                'roomzoomcall.meeting_url',
                'student.username as student_name', // Ambil username student
                'tutor.username as tutor_name' // Ambil username tutor
            )
            ->orderBy('transactions.created_at', 'desc')
            ->paginate(10);
    
        // Return ke view yang sesuai dengan role
        return view($view, compact('transactions'));
    }
    
    
    
}
