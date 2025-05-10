<?php

namespace App\Http\Controllers\Forum;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PostController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'thread_id' => 'required|exists:threads,id',
            'content' => 'required|string',
            'parent_id' => 'nullable|exists:posts,id',
        ]);

        Post::create([
            'thread_id' => $request->thread_id,
            'user_id' => session('id'), // ✅ ambil dari session
            'content' => $request->content,
            'parent_id' => $request->parent_id, // ⬅️ ini juga harus ada
        ]);

        return redirect()->back()->with('success', 'Komentar berhasil dikirim.');
    }

    public function destroy($id)
    {
        if (session('role') != 3) {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk menghapus komentar.');
        }

        $post = Post::find($id);

        if (!$post) {
            return redirect()->back()->with('error', 'Komentar tidak ditemukan.');
        }

        $post->delete();

        return redirect()->back()->with('success', 'Komentar berhasil dihapus.');
    }



}
