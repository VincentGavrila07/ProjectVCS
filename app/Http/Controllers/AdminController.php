<?php

namespace App\Http\Controllers;

use App\Models\MsUser;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class AdminController extends Controller
{
    public function index()
    {
        // Menghitung jumlah user berdasarkan role
        $role1Count = MsUser::where('role', 1)->count();
        $role2Count = MsUser::where('role', 2)->count();
        
        // Mengirim data ke view
        return view('Admin.index', compact('role1Count', 'role2Count'));  // Pastikan nama view-nya sesuai
    }

    public function userList(Request $request)
    {
        // Ambil data user dengan kondisi pencarian jika ada
        $query = MsUser::query()
        ->whereNot('role',3);
    
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('id', 'LIKE', "%{$search}%")
            ->orwhere('username', 'like', '%' . $search . '%')
                  ->orWhere('role', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
        }
    
        // Menangani pengurutan berdasarkan kolom dan arah
        if ($request->has('sort')) {
            $sortColumn = $request->input('sort');
            $sortOrder = $request->input('order', 'asc'); // Default adalah ascending
            $query->orderBy($sortColumn, $sortOrder);
        }
    
        // Ambil data user
        $users = $query->paginate(10);
    
        // Kirim data ke view
        return view('admin.userList', compact('users'));
    }

    public function tutorList(Request $request)
    {
        // Ambil data user dengan kondisi pencarian jika ada
        $query = MsUser::query()
        ->where('role', 1);
    
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('id', 'LIKE', "%{$search}%")
                ->orwhere('username', 'like', '%' . $search . '%')
                  ->orWhere('role', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
        }
    
        // Menangani pengurutan berdasarkan kolom dan arah
        if ($request->has('sort')) {
            $sortColumn = $request->input('sort');
            $sortOrder = $request->input('order', 'asc'); // Default adalah ascending
            $query->orderBy($sortColumn, $sortOrder);
        }
    
        // Ambil data user
        $users = $query->paginate(10);
    
        // Kirim data ke view
        return view('admin.tutorList', compact('users'));
    }

    public function deleteTutor($id)
    {
        try {
            $user = MsUser::findOrFail($id);
            $user->delete();
    
            // Jika request mengharapkan JSON, kembalikan respons JSON
            if (request()->expectsJson()) {
                return response()->json(['success' => true, 'message' => 'Tutor berhasil dihapus.']);
            }
    
            // Jika bukan AJAX, lakukan redirect
            return redirect()->route('tutorList')->with('success', 'Tutor berhasil dihapus.');
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat menghapus tutor.'], 500);
            }
            return redirect()->route('tutorList')->with('error', 'Terjadi kesalahan saat menghapus tutor.');
        }
    }
    

    
    public function pelajarList(Request $request)
    {
        // Ambil data user dengan kondisi pencarian jika ada
        $query = MsUser::query()
        ->where('role', 2);
    
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('id', 'LIKE', "%{$search}%")
                ->orwhere('username', 'like', '%' . $search . '%')
                  ->orWhere('role', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
        }
    
        // Menangani pengurutan berdasarkan kolom dan arah
        if ($request->has('sort')) {
            $sortColumn = $request->input('sort');
            $sortOrder = $request->input('order', 'asc'); // Default adalah ascending
            $query->orderBy($sortColumn, $sortOrder);
        }
    
        // Ambil data user
        $users = $query->paginate(10);
    
        return view('admin.pelajarList', compact('users'));
    }

    public function deletePelajar($id)
    {
        try {
            \Log::info('DeletePelajar dipanggil untuk ID: ' . $id);
            $user = MsUser::findOrFail($id);
            $user->delete();
            \Log::info('Pelajar dengan ID: ' . $id . ' berhasil dihapus.');
            if (request()->expectsJson()) {
                return response()->json(['success' => true, 'message' => 'Pelajar berhasil dihapus.']);
            }
            return redirect()->route('pelajarList')->with('success', 'Pelajar berhasil dihapus.');
        } catch (\Exception $e) {
            \Log::error('Error saat menghapus Pelajar: ', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat menghapus Pelajar.'], 500);
        }
    }

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




    
    
    
}
