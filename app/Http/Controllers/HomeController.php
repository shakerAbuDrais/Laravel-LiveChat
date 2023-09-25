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

        $obtainedMarks = [];

        foreach ($subjects as $subject) {
            // Retrieve the obtained_mark for the user-subject combination
            $obtainedMark = $user->subjects->where('id', $subject->id)->first()->pivot->obtained_mark;

            // Store it in the obtainedMarks array with the subject ID as the key
            $obtainedMarks[$subject->id] = $obtainedMark;
        }

        $data = [
            'username' => $user->name,
            'email' => $user->email,
            'subjects' => $subjects,
            'obtainedMarks' => $obtainedMarks, // Pass the obtainedMarks array to the view
        ];

        return view('home', $data);
    }
}
