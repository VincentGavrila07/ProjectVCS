<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\MsUser;

class ProfileController extends Controller
{
    // Tampilkan halaman edit profile
    public function edit()
    {
        // Ambil user dari database menggunakan id yang disimpan di session
        $user = MsUser::find(session('id'));
        if($user->role == 2){

            return view('mainpage.pelajar.profile', compact('user'));
        }else if($user->role == 1){
            return view('mainpage.tutor.profile', compact('user'));
        };
    }

    // Proses update profile
    public function update(Request $request)
    {
        // Cari user berdasarkan ID yang tersimpan di session
        $user = MsUser::find(session('id'));

        // Validasi input
        $request->validate([
            'username' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        // Update username
        $user->username = $request->input('username');

        // Jika ada file foto, simpan file tersebut
        if ($request->hasFile('photo')) {
            // Jika user sudah punya foto, hapus foto lama (opsional)
            if ($user->image) {
                Storage::disk('public')->delete($user->image);
            }
            // Simpan file ke folder 'profile' di disk 'public'
            $path = $request->file('photo')->store('profile', 'public');
            $user->image = $path;
        }

        $user->save();

        // Perbarui data di session agar selalu sinkron dengan database
        session([
            'username' => $user->username,
            'image' => $user->image,
        ]);

        return redirect()->back()->with('success', 'Profile berhasil diupdate.');
    }
}
