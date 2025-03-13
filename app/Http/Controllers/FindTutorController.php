<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FindTutorController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('msuser')
            ->where('role', 1)
            ->leftJoin('mssubject', 'msuser.subjectClass', '=', 'mssubject.id')
            ->select(
                'msuser.*',
                'mssubject.subjectName as subject_name',
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
                ")
            );

        // Search berdasarkan username, TeacherId, atau subject_name
        if ($request->has('search') && $request->search != '') {
            $query->where(function ($q) use ($request) {
                $q->where('msuser.username', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('msuser.TeacherId', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('mssubject.subjectName', 'LIKE', '%' . $request->search . '%');
            });
        }

        // Filter berdasarkan range harga
        if ($request->has('min_price') && $request->min_price != '') {
            $query->where('msuser.price', '>=', $request->min_price);
        }
        if ($request->has('max_price') && $request->max_price != '') {
            $query->where('msuser.price', '<=', $request->max_price);
        }

        // Filter berdasarkan pengalaman (bulan)
        if ($request->has('min_experience') && $request->min_experience != '') {
            $query->whereRaw("TIMESTAMPDIFF(MONTH, msuser.created_at, NOW()) >= ?", [$request->min_experience]);
        }

        // Filter berdasarkan subject
        if ($request->has('subject') && $request->subject != '') {
            $query->where('msuser.subjectClass', $request->subject);
        }

        $tutors = $query->get();
        
        // Ambil daftar mata pelajaran untuk dropdown filter
        $subjects = DB::table('mssubject')->select('id', 'subjectName')->get();

        return view('mainpage/pelajar/findTutor', compact('tutors', 'subjects'));
    }
}
