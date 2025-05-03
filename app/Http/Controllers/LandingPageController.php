<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LandingPageController extends Controller
{
    public function getTutors()
    {
        $tutors = DB::table('msuser')
            ->where('role', 1)
            ->where('isAvailable',1)
            ->leftJoin('mssubject', 'msuser.subjectClass', '=', 'mssubject.id')
            ->select(
                'msuser.id',
                'msuser.username as name',
                'msuser.price',
                DB::raw("IFNULL(CONCAT('".url('storage')."/', msuser.image), '".url('images/user.jpg')."') as image"),
                'mssubject.subjectName as specialty',
                DB::raw("
                    CASE 
                        WHEN TIMESTAMPDIFF(YEAR, msuser.created_at, NOW()) > 0 
                            THEN CONCAT(TIMESTAMPDIFF(YEAR, msuser.created_at, NOW()), ' tahun')
                        WHEN TIMESTAMPDIFF(MONTH, msuser.created_at, NOW()) > 0 
                            THEN CONCAT(TIMESTAMPDIFF(MONTH, msuser.created_at, NOW()), ' bulan')
                        WHEN TIMESTAMPDIFF(DAY, msuser.created_at, NOW()) > 0 
                            THEN CONCAT(TIMESTAMPDIFF(DAY, msuser.created_at, NOW()), ' hari')
                        ELSE '1 hari'
                    END as experience
                "),
                DB::raw("FLOOR(RAND() * 100) + 1 as rating")
            )
            ->get()
            ->reject(function ($tutor) {
                return empty($tutor->name) || empty($tutor->price) || empty($tutor->specialty);
            }) // Filter yang memiliki atribut kosong atau null
            ->shuffle() // Acak data sebelum mengambil 6 tutor
            ->take(6); // Ambil 6 tutor untuk landing page

        return response()->json($tutors)->header('Access-Control-Allow-Origin', '*');
    }

    public function getThread(Request $request){
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
            ->paginate(4); // PAGINATION aktif
    
        return response()->json($threads)->header('Access-Control-Allow-Origin', '*');
    }
    
    
}
