<?php

use Illuminate\Support\Facades\Route;
use App\Models\JobApplication;

Route::get('/debug/cv-files', function() {
    $apps = JobApplication::where('additional_file', '!=', null)
        ->select('job_application_id', 'additional_file', 'student_id')
        ->limit(5)
        ->get();

    return response()->json([
        'total' => count($apps),
        'data' => $apps->map(function($app) {
            return [
                'id' => $app->job_application_id,
                'filename' => $app->additional_file,
                'cv_url' => $app->getCvUrl(),
                'file_exists_new_path' => \Illuminate\Support\Facades\Storage::disk('public')->exists('cv_applications/' . $app->additional_file),
                'file_exists_old_path' => \Illuminate\Support\Facades\Storage::disk('public')->exists('applications/cvs/' . $app->additional_file),
            ];
        }),
    ], 200);
});
