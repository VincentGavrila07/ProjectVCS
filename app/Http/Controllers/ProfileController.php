<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\MsUser;
use App\Models\MsSubject;

class ProfileController extends Controller
{
    // Tampilkan halaman edit profile untuk Pelajar
    public function editPelajar()
    {
        $user = MsUser::find(session('id'));
        return view('mainpage.pelajar.profile', compact('user'));
    }

    // Tampilkan halaman edit profile untuk Tutor
    public function editTutor()
    {
        $user = MsUser::find(session('id'));
        $subjects = MsSubject::all(); // Ambil semua keahlian dari database
        return view('mainpage.tutor.profile', compact('user', 'subjects'));
    }

    // Proses update profile untuk Pelajar
    public function updatePelajar(Request $request)
    {
        $user = MsUser::find(session('id'));

        $request->validate([
            'username' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        $updateData = [
            'username' => $request->input('username'),
        ];

        if ($request->hasFile('photo')) {
            if ($user->image) {
                Storage::disk('public')->delete($user->image);
            }
            $path = $request->file('photo')->store('profile', 'public');
            $updateData['image'] = $path;
        }

        // Pastikan created_at tidak berubah
        MsUser::where('id', session('id'))->update([
            'username' => $request->username,
            'image' => $updateData['image'] ?? $user->image,
            'created_at' => $user->created_at, // Tetap gunakan nilai lama
        ]);

        // Perbarui session
        session([
            'username' => $request->input('username'),
            'image' => $updateData['image'] ?? $user->image,
        ]);

        return redirect()->back()->with('success', 'Profile berhasil diperbarui.');
    }


    // Proses update profile untuk Tutor (harus isi harga & keahlian)
    public function updateTutor(Request $request)
    {
        $user = MsUser::find(session('id'));
    
        $request->validate([
            'username' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'specialty' => 'required|exists:mssubject,id',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);
    
        $updateData = [
            'username' => $request->input('username'),
            'price' => $request->input('price'),
            'subjectClass' => $request->input('specialty')
        ];
    
        if ($request->hasFile('photo')) {
            if ($user->image) {
                Storage::disk('public')->delete($user->image);
            }
            $path = $request->file('photo')->store('profile', 'public');
            $updateData['image'] = $path;
        }
    
        // Matikan timestamps sebelum update
        MsUser::where('id', session('id'))->update([
            'username' => $request->username,
            'price' => $request->price,
            'subjectClass' => $request->specialty,
            'image' => $updateData['image'] ?? $user->image,
            'created_at' => $user->created_at, // Pastikan tetap pakai nilai lama
        ]);
        
    
        session([
            'username' => $request->input('username'),
            'price' => $request->input('price'),
            'subjectClass' => $request->input('specialty'),
            'image' => $updateData['image'] ?? $user->image,
        ]);
    
        return redirect()->back()->with('success', 'Profile berhasil diupdate.');
    }
    

}
