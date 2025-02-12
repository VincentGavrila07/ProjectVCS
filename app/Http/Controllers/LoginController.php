<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MsUser;
class LoginController extends Controller
{
    public function showLogin(){
        return view('login');
    }

    public function login(Request $request){

        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        // $pelajar = MsUser::where('role', 2);
        // $tutor = MsUser::where('role', 1);

        $user = MsUser::where('email', $request->email)->first();
        if ($user && $request->password == $user->password) {
            // Simpan user di sesi
            session(['id' => $user->id, 'username' => $user->username, 'role' => $user->role]);

          
            if ($user->role == 1) {
                return redirect('/tutor');  // ini buat tutor
            } else {
                return redirect('/pelajar');  // pelajar
        }

       
        return redirect()->back()->withErrors([
            'username' => 'Username atau password salah.',
        ]);
    }
    }
}
