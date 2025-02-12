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
    // Hapus dd($request->all()) setelah verifikasi
    $validated = $request->validate([
        'username' => 'required|string|max:40',
        'email' => 'required|email|max:60|unique:users,email',
        'password' => 'required|string|min:8',  // Pastikan password_confirmation ada di form
        'role' => 'required|in:1,2,3', // Validasi role (1 = Tutor, 2 = Pelajar,3 = Admin)
    ]);

    // Simpan ke database
    MsUser::create([
        'username' => $request->username,
        'email' => $request->email,
        'password' => Hash::make($request->password), // Hash password
        'role' => $request->role, // Role 1 = Tutor, 2 = Pelajar
        'TeacherId' => $request->role == 1 ? 'T-' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT) : null, // Generate TeacherId jika role adalah Tutor
    ]);

    return redirect('/login')->with('success', 'Registrasi berhasil, silakan login.');
}

}
