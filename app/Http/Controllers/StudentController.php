<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;

class StudentController extends Controller
{
    // GET /students/me
    public function me(Request $request)
    {
        $student = Student::where('user_id', $request->user()->id)->first();

        if (!$student) {
            return response()->json([
                'message' => 'Student profile not found'
            ], 404);
        }

        return response()->json($student);
    }

    // PUT /students/me
    public function updateMe(Request $request)
    {
        $student = $request->user()->student;

        if (!$student) {
            return response()->json([
                'message' => 'Student profile not found'
            ], 404);
        }

        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'nis' => 'nullable|string|max:50',
            'gender' => 'nullable|in:L,P',
            'birth_info' => 'nullable|string|max:255',
            'major' => 'nullable|string|max:255',
            'graduation_year' => 'nullable|integer',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'resume_url' => 'nullable|url',
            'profile_picture' => 'nullable|string',
            'status' => 'nullable|string|max:50',
            'alumni_flag' => 'required|boolean',
        ]);

        $student->update($validated);

        return response()->json([
            'message' => 'Profile updated successfully',
            'data' => $student
        ]);
    }
}
