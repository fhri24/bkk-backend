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
        $student = Student::where('user_id', $request->user()->id)->first();

        if (!$student) {
            return response()->json([
                'message' => 'Student profile not found'
            ], 404);
        }

        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'major' => 'nullable|string|max:255',
            'graduation_year' => 'nullable|integer',
            'resume_url' => 'nullable|url',
            'alumni_flag' => 'required|boolean',
        ]);

        $student->update($validated);

        return response()->json([
            'message' => 'Profile updated successfully',
            'data' => $student
        ]);
    }
}
