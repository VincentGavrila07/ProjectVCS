<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class TutorController extends Controller
{
    public function index()
    {
        return view('mainpage.tutor', [
            'username' => session('username'),
            'teacherId' => session('teacherId')
        ]);
    }
}
