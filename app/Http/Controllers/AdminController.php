<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index()
    {
        $users = User::all();
        $subjects = Subject::all();
        $selectedUser = null;

        if (request()->has('user_id')) {
            $selectedUser = User::find(request()->get('user_id'));
            $unassignedSubjects = $subjects->diff($selectedUser->subjects);
        } else {
            $unassignedSubjects = $subjects;
        }

        return view('admin', [
            'users' => $users,
            'subjects' => $subjects,
            'unassignedSubjects' => $unassignedSubjects,
            'selectedUser' => $selectedUser,
        ]);
    }

    public function store(Request $request)
    {
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'User created successfully.',
        ]);
    }

    public function createSubject(Request $request)
    {
        $subject = new Subject();
        $subject->subject = $request->subject;
        $subject->pass_mark = $request->pass_mark;

        $subject->save();

        return response()->json([
            'success' => true,
            'message' => 'User created successfully.',
        ]);
    }

    public function getUnassignedSubjects(Request $request) {
        $userId = $request->input('user_id');

        // Get the subjects not assigned to the selected user
        $unassignedSubjects = Subject::whereDoesntHave('users', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->get();

        return response()->json($unassignedSubjects);
    }

    public function getUsersWithoutSubject(Request $request) {
        $subjectId = $request->input('subject_id');

        // Get the users who do not have the selected subject
        $usersWithoutSubject = User::whereDoesntHave('subjects', function ($query) use ($subjectId) {
            $query->where('subject_id', $subjectId);
        })->get();

        return response()->json($usersWithoutSubject);
    }
}
