<?php

use App\Http\Controllers\api\AdminController;
use App\Http\Controllers\api\AuthAPIController;
use App\Http\Controllers\api\PasswordResetController;
use App\Http\Controllers\api\QuestionController;
use App\Http\Controllers\api\QuizAssignmentController;
use App\Http\Controllers\api\QuizController;
use App\Http\Controllers\api\StudentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// // Admin Login/Logout Routes
// Route::post('admin/login', [AuthAPIController::class, 'login'])->middleware(['logs']);
// Route::post('admin/logout', [AuthAPIController::class, 'logout'])->middleware(['logs','auth:sanctum']);

// Only Admin can create/Add manager by sending him email to setup the password
Route::post('admin/add-manager', [AdminController::class, 'addManager'])
    ->middleware(['auth:sanctum']);

//Get route would show the password reset form UI
Route::get('password-reset/{token}/{name}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');

// Upon submission Post route would confirm the password from the form and then finally updates it 
Route::post('password-reset/{token}', [PasswordResetController::class, 'resetPassword'])->name('password.update');

// Resend the setup link If the user wants
Route::get('password/resend/{email}', [PasswordResetController::class, 'resendPasswordReset'])->name('password.resend');

// // Manager Login/Logout routes using new credentials
// Route::post('manager/login', [AuthAPIController::class, 'login'])->middleware(['logs']);
// Route::post('manager/logout', [AuthAPIController::class, 'logout'])->middleware(['logs','auth:sanctum']);

// Students can be Registered If Admin Upon subon submission Accepts that student
Route::post('students/register', [StudentController::class, 'addStudent']);

Route::middleware(['auth:sanctum', 'role:Admin'])->group(function () {
    Route::get('admin/accept-student/{studentId}/{admin_login_token}', [AdminController::class, 'approveStudent'])->name('admin.approve-student');
    Route::get('admin/reject-student/{studentId}/{admin_login_token}', [AdminController::class, 'rejectStudent'])->name('admin.reject-student');
});

// // Student Login/Logout routes using new credentials
// Route::post('student/login', [AuthAPIController::class, 'login'])->middleware(['logs']);
// Route::post('student/logout', [AuthAPIController::class, 'logout'])->middleware(['logs','auth:sanctum']);

// Grouped Login/Logout routes for Admin, Manager, and Student
Route::prefix('{role}')->group(function () {
    Route::post('login', [AuthAPIController::class, 'login'])->middleware(['logs']);
    Route::post('logout', [AuthAPIController::class, 'logout'])->middleware(['logs', 'auth:sanctum']);
})->whereIn('role', ['admin', 'manager', 'student']);


// Quiz Creation By Admin
// In routes/api.php
Route::post('quiz', [QuizController::class, 'create'])->middleware(['auth:sanctum', 'role:Admin']);
// Quiz Questions creation after Admin
Route::post('quiz/{quiz_id}/questions', [QuestionController::class, 'create'])->middleware('auth:sanctum', 'role:Admin');

// After Quiz Creation only Manager assigns quiz to students
Route::post('assign-quiz', [QuizAssignmentController::class, 'assign'])->middleware(['auth:sanctum', 'role:Admin|Manager' , 'can:assign quizzes' ]);

// Student attempts quiz
Route::post('quiz/{quiz_id}/attempt', [QuizAssignmentController::class, 'attempt'])->middleware('auth:sanctum', 'role:Student');

// Check if quiz is expired
Route::get('quiz/{quiz_id}/status', [QuizAssignmentController::class, 'checkStatus'])->middleware('auth:sanctum', 'role:Student');

// Route to Upload Video of solving the specific Quiz
Route::post('quiz/{quiz_id}/upload-video', [QuizAssignmentController::class, 'uploadVideo'])->middleware('auth:sanctum', 'role:Student');

// Route to fetch assigned quizzes for students
Route::get('quizzes/assigned', [QuizAssignmentController::class, 'getAssignedQuizzes'])
    ->middleware(['auth:sanctum', 'role:Student']);





