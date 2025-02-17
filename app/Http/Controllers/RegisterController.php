<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MsUser;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showRegister()
    {
        return view('register');
    }

    // app/Http/Controllers/RegisterController.php

    public function register(Request $request)
    {
        // Cek data yang dikirim sebelum validasi
        // dd($request->all());
    
        $validated = $request->validate([
            'username' => 'required|string|max:40',
            'email' => 'required|email|max:60|unique:msuser,email',
            'password' => 'required|string|min:8',
            'role' => 'required|in:1,2,3',
        ]);
    
        MsUser::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'TeacherId' => $request->role == 1 ? 'T-' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT) : null,
        ]);
    
        return redirect('/login')->with('success', 'Registrasi berhasil, silakan login.');
    }
    

}