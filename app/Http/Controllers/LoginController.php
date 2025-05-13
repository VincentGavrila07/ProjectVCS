<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\MsUser;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLogin()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string|min:8',
        ]);
    
        $user = MsUser::where('email', $request->email)->first();
    
        if (!$user) {
            return redirect()->back()->withErrors(['email' => 'Email tidak ditemukan.']);
        }
    
        if (!Hash::check($request->password, $user->password)) {
            return redirect()->back()->withErrors(['password' => 'Password salah.']);
        }
    
        // Menyimpan data user dalam sesi
        session([
            'id' => $user->id,
            'username' => $user->username,
            'role' => $user->role,
            'email' => $user->email,
            'image' => $user->image,
            'TeacherId' => $user->TeacherId ?? null,
            'price' => $user->price ?? null, 
            'subjectClass' => $user->subjectClass,
            'rating' => $user->rating,
        ]);
        
    
        // Redirect ke halaman yang sesuai berdasarkan role
        // return $user->role == 1
        //     ? redirect()->route('tutor')
        //     : redirect('/pelajar');

        if ($user->role == 3) { // Admin
            return redirect()->route('admin');
        } elseif ($user->role == 1) { // Tutor
            return redirect()->route('tutor');
        } else { // Pelajar
            return redirect('/pelajar');
        }
    }

    public function logout(Request $request)
    {
        // Ambil ID tutor dari session sebelum menghapus session
        $tutorId = session('id');
    
        if ($tutorId) {
            // Update status isAvailable menjadi false
            $tutor = \App\Models\MsUser::find($tutorId);
            if ($tutor) {
                $tutor->isAvailable = false;
                $tutor->save();
            }
        }
    
        // Hapus semua session
        $request->session()->flush();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    
        return redirect()->route('landing');
    }
    

    

}
