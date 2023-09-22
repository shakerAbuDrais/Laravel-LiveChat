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
        $subjects = Subject::whereHas('users', function ($query) use ($user) {
            $query->where('users.id', $user->id);
        })->get();

        $data = [
            'username' => $user->name,
            'email' => $user->email,
            'subjects' => $subjects,
        ];

        return view('home', $data);
    }
}
