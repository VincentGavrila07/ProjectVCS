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
}
