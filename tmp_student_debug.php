<?php

require __DIR__ . '/vendor/autoload.php';

$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$nis = '0095647260';
$student = App\Models\Student::where('nisn', $nis)->orWhere('nis', $nis)->first();

if (! $student) {
    echo "STUDENT NONE\n";
    exit(0);
}

$user = App\Models\User::find($student->user_id);

echo "STUDENT: student_id={$student->student_id} user_id={$student->user_id} nis={$student->nis} nisn={$student->nisn} major={$student->major} year={$student->graduation_year} status={$student->status}\n";

if ($user) {
    $roleName = $user->role ? $user->role->name : 'none';
    echo "USER: id={$user->id} email={$user->email} role={$roleName} password=" . ($user->password ? 'hashed' : 'null') . "\n";
} else {
    echo "USER NONE\n";
}
