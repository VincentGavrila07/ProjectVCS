<?php

namespace App\Http\Controllers;

use App\Models\MsUser;
use App\Models\Transaction;
use App\Models\MsSubject;
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
        // Query data user dan join dengan msrole untuk mendapatkan nama role
        $query = DB::table('msuser')
            ->whereNot('msuser.role', 3)
            ->leftJoin('msrole', 'msuser.role', '=', 'msrole.id') // Join ke msrole
            ->select('msuser.*', 'msrole.name as role_name'); // Ambil semua data msuser + role_name
    
        // Filter pencarian jika ada
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('msuser.id', 'LIKE', "%{$search}%")
                    ->orWhere('msuser.username', 'LIKE', "%{$search}%")
                    ->orWhere('msrole.name', 'LIKE', "%{$search}%") // Cari berdasarkan nama role
                    ->orWhere('msuser.email', 'LIKE', "%{$search}%");
            });
        }
    
        // Sorting jika ada request sort
        if ($request->has('sort')) {
            $sortColumn = $request->input('sort');
            $sortOrder = $request->input('order', 'asc'); // Default ascending
            $query->orderBy($sortColumn, $sortOrder);
        }
    
        // Ambil data dengan pagination
        $users = $query->paginate(10);
    
        // Kirim data ke view
        return view('admin.userList', compact('users'));
    }
    

    
    public function tutorList(Request $request)
    {
        // Ambil data tutor dengan LEFT JOIN ke mssubject dan wallet
        $query = DB::table('msuser')
            ->where('msuser.role', 1)
            ->leftJoin('mssubject', 'msuser.subjectClass', '=', 'mssubject.id')
            ->leftJoin('wallets', 'msuser.id', '=', 'wallets.user_id') // Join ke wallet
            ->select(
                'msuser.*', 
                'mssubject.subjectName as subject_name',
                'wallets.balance as wallet_balance' // Ambil saldo wallet
            );

        // Filter berdasarkan pencarian (search)
        if ($request->has('search') && $request->search != '') {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('msuser.id', 'LIKE', "%{$search}%")
                ->orWhere('msuser.username', 'LIKE', "%{$search}%")
                ->orWhere('msuser.email', 'LIKE', "%{$search}%")
                ->orWhere('mssubject.subjectName', 'LIKE', "%{$search}%")
                ->orWhere('wallets.balance', 'LIKE', "%{$search}%"); // Tambahkan pencarian berdasarkan saldo wallet
            });
        }

        // Sorting berdasarkan kolom tertentu
        if ($request->has('sort')) {
            $sortColumn = $request->input('sort');
            $sortOrder = $request->input('order', 'asc'); // Default ascending
            $query->orderBy($sortColumn, $sortOrder);
        }

        // Ambil data dengan pagination (10 per halaman)
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
        // Query data user (role = 2) dan join dengan wallet untuk mendapatkan balance
        $query = DB::table('msuser')
            ->where('msuser.role', 2)
            ->leftJoin('wallets', 'msuser.id', '=', 'wallets.user_id') // Join ke wallet
            ->select('msuser.*', 'wallets.balance as wallet_balance'); // Ambil balance dari wallet

        // Filter pencarian jika ada
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('msuser.id', 'LIKE', "%{$search}%")
                    ->orWhere('msuser.username', 'LIKE', "%{$search}%")
                    ->orWhere('msuser.email', 'LIKE', "%{$search}%");
            });
        }

        // Menangani pengurutan berdasarkan kolom dan arah
        if ($request->has('sort')) {
            $sortColumn = $request->input('sort');
            $sortOrder = $request->input('order', 'asc'); // Default ascending
            $query->orderBy($sortColumn, $sortOrder);
        }

        // Ambil data dengan pagination
        $users = $query->paginate(10);

        // Kirim data ke view
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


    public function subjectList(Request $request)
    {
        $query = MsSubject::query();

        // Cek apakah ada pencarian berdasarkan student_id atau tutor_id
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('id', 'LIKE', "%{$search}%")
                ->orwhere('subjectName', 'LIKE', "%{$search}%");
        }

        $subject = $query->orderBy('id', 'desc')->paginate(10);

        return view('admin.subject', compact('subject'));
    }
    
    public function destroySubject($id)
    {
        $subject = MsSubject::find($id);

        if (!$subject) {
            return response()->json(['success' => false, 'message' => 'Subject tidak ditemukan.'], 404);
        }
        
        DB::table('msuser')->where('subjectClass', $id)->update(['subjectClass' => null]);
        
        // Pastikan hanya menghapus subject, bukan tutor
        DB::table('transactions')->where('subject_id', $id)->delete(); 
        
        // Hapus subject setelah data terkait dihapus
        $subject->delete();
        
        return response()->json(['success' => true, 'message' => 'Subject berhasil dihapus.']);
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'subjectName' => 'required|string|max:255'
        ]);
        
        MsSubject::create([
            'subjectName' => $request->subjectName
        ]);
        
        return back()->with('success', 'Subject berhasil ditambahkan!');
    }
    
    public function update(Request $request, $id)
    {
        $subject = MsSubject::findOrFail($id);
        $subject->update(['subjectName' => $request->subjectName]);
        
        return response()->json(['success' => true]);
    }
    

    public function withdrawList(Request $request)
    {
        $query = DB::table('MsWithdraw')
            ->join('MsUser', 'MsWithdraw.user_id', '=', 'MsUser.id')
            ->select(
                'MsWithdraw.id',
                'MsUser.username',
                'MsWithdraw.bank_name',
                'MsWithdraw.account_number',
                'MsUser.email',
                'MsWithdraw.amount',
                'MsWithdraw.status',
                'MsWithdraw.created_at'
            );
    
        // Pencarian
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('MsWithdraw.id', 'like', "%$search%")
                  ->orWhere('MsUser.username', 'like', "%$search%")
                  ->orWhere('MsUser.email', 'like', "%$search%")
                  ->orWhere('MsUser.bank_name', 'like', "%$search%")
                  ->orWhere('MsWithdraw.amount', 'like', "%$search%");
            });
        }
    
        // Sorting
        $sortColumn = $request->input('sort', 'MsWithdraw.id'); // Default sort by ID
        $query->orderBy($sortColumn, 'asc');
    
        // Ambil data dengan pagination
        $withdraws = $query->paginate(10);
    
        return view('admin.withdrawReq', compact('withdraws'));
    }
    
    
    
    
    
}
