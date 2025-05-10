<?php

namespace App\Http\Controllers\Forum;

use App\Models\Thread;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\MsSubject; 
use Illuminate\Support\Facades\DB;

class ThreadController extends Controller
{


    public function index(Request $request)
    {
        $search = $request->input('search');
    
        $threads = DB::table('threads')
            ->join('msuser', 'threads.user_id', '=', 'msuser.id')
            ->leftJoin('mssubject as user_subjects', 'msuser.subjectClass', '=', 'user_subjects.id')
            ->leftJoin('mssubject as thread_subjects', 'threads.subject_id', '=', 'thread_subjects.id')
            ->select(
                'threads.*',
                'msuser.username',
                'msuser.image',
                'msuser.TeacherId as teacherid',
                'msuser.role',
                'user_subjects.subjectName as user_subject',
                'thread_subjects.subjectName as thread_subject'
            )
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('msuser.username', 'like', '%' . $search . '%')
                      ->orWhere('msuser.TeacherId', 'like', '%' . $search . '%')
                      ->orWhere('user_subjects.subjectName', 'like', '%' . $search . '%')
                      ->orWhere('thread_subjects.subjectName', 'like', '%' . $search . '%')
                      ->orWhere('threads.title', 'like', '%' . $search . '%');
                });
            })
            ->orderByDesc('threads.created_at')
            ->get();
    
        return view('forum.threads.index', compact('threads'));
    }
    

    public function show($id)
    {
        $thread = DB::table('threads')
            ->join('msuser', 'threads.user_id', '=', 'msuser.id')
            ->leftJoin('mssubject as user_subjects', 'msuser.subjectClass', '=', 'user_subjects.id')
            ->leftJoin('mssubject as thread_subjects', 'threads.subject_id', '=', 'thread_subjects.id')
            ->select(
                'threads.*',
                'msuser.username',
                'msuser.image',
                'msuser.role',
                'msuser.TeacherId as teacherid',
                'user_subjects.subjectName as user_subject',
                'thread_subjects.subjectName as thread_subject'
            )
            ->where('threads.id', $id)
            ->first();
    
        // Ambil semua posts yang berhubungan dengan thread ini
        $posts = DB::table('posts')
            ->join('msuser', 'posts.user_id', '=', 'msuser.id')
            ->select(
                'posts.*',
                'msuser.username',
                'msuser.image',
                'msuser.TeacherId as teacherid',
                'msuser.role'
            )
            ->where('posts.thread_id', $id)
            ->orderBy('posts.created_at')
            ->get();
    
        // Kelompokkan berdasarkan parent_id untuk mendapatkan struktur parent-child
        $groupedPosts = $posts->groupBy('parent_id');
    
        return view('forum.threads.show', compact('thread', 'groupedPosts'));
    }
    
    
    public function create()
    {
        $subjects = MsSubject::all(); // ambil semua kategori
    
        return view('forum.threads.create', compact('subjects'));
    }
    

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'subject_id' => 'nullable|exists:mssubject,id',
        ]);
        

        Thread::create([
            'title' => $request->title,
            'content' => $request->content,
            'subject_id' => $request->subject_id,
            'user_id' => session('id'), // pastikan user login
        ]);

        return redirect()->route('forum.threads.index')->with('success', 'Thread berhasil dibuat!');
    }

    public function destroy($id)
{
        if (session('role') != 3) {
            return redirect()->route('forum.threads.index')->with('error', 'Anda tidak memiliki izin untuk menghapus thread.');
        }

        $thread = Thread::find($id);

        if (!$thread) {
            return redirect()->route('forum.threads.index')->with('error', 'Thread tidak ditemukan.');
        }

        $thread->delete();

        return redirect()->route('forum.threads.index')->with('success', 'Thread berhasil dihapus.');
    }

}
