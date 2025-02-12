<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DbConnectController extends Controller
{
    public function checkConnection()
    {
        try {
            // Coba melakukan query sederhana
            DB::connection()->getPdo();
            return response()->json(['message' => 'Koneksi ke database berhasil.'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Koneksi ke database gagal: ' . $e->getMessage()], 500);
        }
    }
}