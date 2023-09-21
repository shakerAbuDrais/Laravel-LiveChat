<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        $userId = $user->id;
        $subjects = Subject::where('user_id', $userId)->get();

        $data = [
            'username' => $user->name,
            'email' => $user->email,
            'subjects' => $subjects,
        ];

        return view('home', $data);
    }
}
