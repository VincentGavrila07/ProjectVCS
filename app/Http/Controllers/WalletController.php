<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Models\MsWithdraw;
use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Support\Facades\DB;

class WalletController extends Controller
{
    public function __construct()
    {
        // Set Midtrans configuration
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function index()
    {
        $userId = session('id'); // Ambil user_id dari session
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu.');
        }

        $wallet = Wallet::firstOrCreate(['user_id' => $userId], ['balance' => 0]);
        $withdraws = MsWithdraw::where('user_id', $userId)->orderBy('created_at', 'desc')->get();

        return view('mainpage.wallet.index', compact('wallet','withdraws'));
    }
    public function walletTutor()
    {
        $userId = session('id'); 
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu.');
        }
    
        $wallet = Wallet::firstOrCreate(['user_id' => $userId], ['balance' => 0]);
        
        // Ambil riwayat withdraw tutor
        $withdraws = MsWithdraw::where('user_id', $userId)->orderBy('created_at', 'desc')->get();
    
        return view('mainpage.tutor.wallet', compact('wallet', 'withdraws'));
    }
    public function walletPelajar()
    {
        $userId = session('id'); 
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu.');
        }
    
        $wallet = Wallet::firstOrCreate(['user_id' => $userId], ['balance' => 0]);
        
        // Ambil riwayat withdraw tutor
        $withdraws = MsWithdraw::where('user_id', $userId)->orderBy('created_at', 'desc')->get();
    
        return view('mainpage.pelajar.wallet', compact('wallet', 'withdraws'));
    }
    



    public function requestWithdraw(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:10000', // Minimal withdraw Rp10.000
            'bank_name' => 'required|string',
            'account_number' => 'required|string',
            'account_name' => 'required|string',
        ]);

        $userId = session('id');

        $email = DB::table('MsUser')
            ->where('id', $userId)
            ->value('email'); // Mengambil satu nilai email

        MsWithdraw::create([
            'user_id' => $userId,
            'amount' => $request->amount,
            'bank_name' => $request->bank_name,
            'account_number' => $request->account_number,
            'account_name' => $request->account_name,
            'status' => 'processing' // Status default processing
        ]);

        return back()->with('success', 'Permintaan withdraw sedang diproses.');
    }
    
    public function updateWithdrawStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:processing,canceled,done',
        ]);
    
        $withdraw = MsWithdraw::findOrFail($id);
        $withdraw->update(['status' => $request->status]);
    
        return back()->with('success', 'Status withdraw berhasil diperbarui.');
    }
    

    public function processWithdrawPelajar(Request $request)
    {
        return $this->processWithdraw($request, 'pelajar');
    }
    
    public function processWithdrawTutor(Request $request)
    {
        return $this->processWithdraw($request, 'tutor');
    }
    
    private function processWithdraw(Request $request, $role)
    {
        $userId = session('id');
    
        // Validasi input
        $request->validate([
            'amount' => 'required|numeric|min:10000', // Minimal withdraw Rp 10.000
            'account_number' => 'required|string',
            'bank' => 'required|string',
            'account_name' => 'required|string',
        ]);
    
        // Ambil saldo pengguna
        $wallet = Wallet::where('user_id', $userId)->first();
    
        if (!$wallet || $wallet->balance < $request->amount) {
            return response()->json(['success' => false, 'message' => 'Saldo tidak cukup'], 400);
        }
    
        // Simpan permintaan withdraw ke database
        MsWithdraw::create([
            'user_id' => $userId,
            'amount' => $request->amount,
            'bank_name' => $request->bank,
            'account_number' => $request->account_number,
            'account_name' => $request->account_name,
            'status' => 'processing', // Status default processing
            'role' => $role // Menyimpan apakah ini withdraw pelajar atau tutor
        ]);
    
        // Kurangi saldo di wallet
        $wallet->balance -= $request->amount;
        $wallet->save();
    
        return response()->json(['success' => true, 'message' => 'Permintaan withdraw sedang diproses']);
    }
    

    public function deposit(Request $request)
    {
        $userId = session('id'); // Ambil user_id dari session
        if (!$userId) {
            return response()->json(['error' => 'Anda harus login terlebih dahulu.'], 401);
        }

        $amount = $request->input('amount');

        // Validasi minimal deposit
        if ($amount < 10000) {
            return response()->json(['error' => 'Minimal deposit adalah Rp 10.000'], 400);
        }

        // Buat transaksi di Midtrans
        $transactionDetails = [
            'order_id' => 'DEPOSIT-' . time(),
            'gross_amount' => $amount,
        ];

        $customerDetails = [
            'first_name' => 'User',
            'last_name' => 'Deposit',
            'email' => 'user@example.com',
            'phone' => '08123456789',
        ];

        $transactionData = [
            'transaction_details' => $transactionDetails,
            'customer_details' => $customerDetails,
        ];

        // Generate snap token
        try {
            $snapToken = Snap::getSnapToken($transactionData);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal menghasilkan snap token: ' . $e->getMessage()], 500);
        }

        // Simpan transaksi ke tabel wallettransaction
        DB::beginTransaction();
        try {
            $walletTransaction = new WalletTransaction();
            $walletTransaction->user_id = $userId;
            $walletTransaction->order_id = $transactionDetails['order_id'];
            $walletTransaction->amount = $amount;
            $walletTransaction->status = 'pending';
            $walletTransaction->save();

            DB::commit();

            return response()->json(['snap_token' => $snapToken]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Terjadi kesalahan saat memproses deposit: ' . $e->getMessage()], 500);
        }
    }

    public function handleNotification(Request $request)
    {
        $payload = $request->getContent();
        $notification = json_decode($payload, true);

        
        // Ambil data penting dari notifikasi
        $orderId = $notification['order_id'];
        $statusCode = $notification['status_code'];
        $transactionStatus = $notification['transaction_status'];

        // Cari transaksi berdasarkan order_id
        $walletTransaction = WalletTransaction::where('order_id', $orderId)->first();

        if ($walletTransaction) {
            if ($transactionStatus == 'settlement') {
                // Update status transaksi
                $walletTransaction->status = 'settlement';
                $walletTransaction->save();

                // Tambahkan saldo ke wallet user
                $wallet = Wallet::where('user_id', $walletTransaction->user_id)->first();
                if ($wallet) {
                    $wallet->balance += $walletTransaction->amount;
                    $wallet->save();
                }
            } elseif ($transactionStatus == 'failed') {
                // Update status transaksi
                $walletTransaction->status = 'failed';
                $walletTransaction->save();
            }
        }

        return response()->json(['status' => 'success']);
    }
}