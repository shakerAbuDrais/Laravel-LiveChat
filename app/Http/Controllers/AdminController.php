<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

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
        $user->status = $request->status ?? 'inactive';
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'User created successfully.',
            'user' => $user,
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

    public function getUnassignedSubjects(Request $request)
    {
        $userId = $request->input('user_id');

        // Get the subjects not assigned to the selected user
        $unassignedSubjects = Subject::whereDoesntHave('users', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->get();

        return response()->json($unassignedSubjects);
    }

    public function getUsersWithoutSubject(Request $request)
    {
        $subjectId = $request->input('subject_id');

        // Get the users who do not have the selected subject
        $usersWithoutSubject = User::whereDoesntHave('subjects', function ($query) use ($subjectId) {
            $query->where('subject_id', $subjectId);
        })->get();

        return response()->json($usersWithoutSubject);
    }

    public function assignSubject(Request $request)
    {
        // Get user ID and subject ID from the request
        $userId = $request->input('user_id');
        $subjectId = $request->input('subject_id');

        // Check if the user and subject exist
        $user = User::find($userId);
        $subject = Subject::find($subjectId);

        if (!$user || !$subject) {
            return response()->json(['success' => false, 'message' => 'User or subject not found.']);
        }

        // Check if the user already has the subject assigned
        if ($user->subjects->contains($subjectId)) {
            return response()->json(['success' => false, 'message' => 'User already has this subject.']);
        }

        // Attach the subject to the user
        $user->subjects()->attach($subjectId);

        return response()->json(['success' => true, 'message' => 'Subject assigned successfully']);
    }

    public function storeMark(Request $request)
    {
        $user_id = $request->input('user_id');
        $subject_id = $request->input('subject_id');
        $obtained_mark = $request->input('obtained_mark');

        // Check if the combination of user_id and subject_id exists
        $existingRecord = DB::table('user_subject')
            ->where('user_id', $user_id)
            ->where('subject_id', $subject_id)
            ->first();

        if ($existingRecord) {
            // If the record exists, update the obtained_mark
            DB::table('user_subject')
                ->where('user_id', $user_id)
                ->where('subject_id', $subject_id)
                ->update(['obtained_mark' => $obtained_mark]);
        } else {
            // If the record doesn't exist, insert a new row
            DB::table('user_subject')->insert([
                'user_id' => $user_id,
                'subject_id' => $subject_id,
                'obtained_mark' => $obtained_mark,
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Mark added successfully']);
    }

    public function updateUser(Request $request)
    {
        // Get the user ID from the form data
        $userId = $request->input('user_id');

        // Retrieve the user by their ID
        $user = User::find($userId);

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found'], 404);
        }

        // Update the user's name and email
        $user->name = $request->input('name');
        $user->email = $request->input('email');

        // Check if the 'status' checkbox is checked and update the user's status accordingly
        $user->status = $request->has('status') ? 'active' : 'inactive';

        // Save the updated user
        $user->save();

        return Redirect::back()->with('message', 'Operation Successful !');
    }

    public function deleteUser(Request $request)
    {
        $userId = $request->input('userId');

        // Retrieve the user
        $user = User::find($userId);

        if ($user) {
            // Detach the user from all subjects before deleting
            $user->subjects()->detach();

            // Delete the user
            $user->delete();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }
}
