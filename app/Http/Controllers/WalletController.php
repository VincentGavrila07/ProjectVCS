<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wallet;
use App\Models\WalletTransaction;
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

        return view('mainpage.wallet.index', compact('wallet'));
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