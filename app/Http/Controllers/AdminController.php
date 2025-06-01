<?php

namespace App\Http\Controllers;

use App\Models\MsUser;
use App\Models\Transaction;
use App\Models\MsSubject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\WalletTransaction;
use App\Models\MsWithdraw;
use Carbon\Carbon;


class AdminController extends Controller
{
public function index(Request $request)
{   $selectedMonth = $request->input('month');
    // Total Users
    $totalUsers = MsUser::count();

    // Total Deposit (status: settlement)
    $totalDeposit = WalletTransaction::where('status', 'settlement')->sum('amount');

    // Total Withdraw (status: done)
    $totalWithdraw = MsWithdraw::where('status', 'done')->sum('amount');

    // Jumlah tutor dan pelajar
    $role1Count = MsUser::where('role', 1)->count(); // Tutor
    $role2Count = MsUser::where('role', 2)->count(); // Pelajar

    // Deposit per bulan (settlement only)
    $depositData = WalletTransaction::select(
            DB::raw("DATE_FORMAT(created_at, '%b %Y') as month"),
            DB::raw("SUM(amount) as total")
        )
        ->where('status', 'settlement')
        ->groupBy(DB::raw("DATE_FORMAT(created_at, '%b %Y')"))
        ->orderBy(DB::raw("MIN(created_at)"))
        ->get();

    $depositMonths = $depositData->pluck('month');
    $depositTotals = $depositData->pluck('total');

    // Withdraw per bulan (done only)
    $withdrawData = MsWithdraw::select(
            DB::raw("DATE_FORMAT(created_at, '%b %Y') as month"),
            DB::raw("SUM(amount) as total")
        )
        ->where('status', 'done')
        ->groupBy(DB::raw("DATE_FORMAT(created_at, '%b %Y')"))
        ->orderBy(DB::raw("MIN(created_at)"))
        ->get();

    $withdrawMonths = $withdrawData->pluck('month');
    $withdrawTotals = $withdrawData->pluck('total');

 // Transaksi berhasil (confirmed)
    $transactionQuery = Transaction::select(
        DB::raw("DATE(created_at) as date"),
        DB::raw("SUM(amount) as total")
    )->where('status', 'confirmed');

    if ($selectedMonth) {
        $transactionQuery->whereMonth('created_at', date('m', strtotime($selectedMonth)))
                         ->whereYear('created_at', date('Y', strtotime($selectedMonth)));
    }

    $transactionData = $transactionQuery->groupBy('date')
        ->orderBy('date')
        ->get();

    $transactionDates = $transactionData->pluck('date');
    $transactionTotals = $transactionData->pluck('total');
    $totalBalance = $totalDeposit - $totalWithdraw;
    // Untuk dropdown filter bulan
    $availableMonths = Transaction::where('status', 'confirmed')
        ->select(DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"))
        ->distinct()
        ->orderBy('month', 'desc')
        ->pluck('month');

    return view('admin.index', compact(
        'totalUsers', 'totalDeposit', 'totalWithdraw',
        'role1Count', 'role2Count',
        'depositMonths', 'depositTotals',
        'withdrawMonths', 'withdrawTotals',
        'transactionDates', 'transactionTotals',
        'availableMonths', 'selectedMonth','totalBalance'
    ));
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
                ->orWhere('MsWithdraw.bank_name', 'like', "%$search%")
                ->orWhere('MsWithdraw.amount', 'like', "%$search%");
            });
        }

        // Withdraw Processing
        $withdrawsProcessing = clone $query;
        $withdrawsProcessing = $withdrawsProcessing->where('MsWithdraw.status', 'processing')->paginate(10, ['*'], 'processing_page');

        // Withdraw Done
        $withdrawsDone = clone $query;
        $withdrawsDone = $withdrawsDone->where('MsWithdraw.status', 'done')->paginate(10, ['*'], 'done_page');

        return view('admin.withdrawReq', compact('withdrawsProcessing', 'withdrawsDone'));
    }

    
    
    
    
    
}
