<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\Question;
use App\Models\QuizAssignment;
use App\Models\QuizOption;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class QuizAssignmentController extends Controller
{
    public function assign(Request $request)
{

    $user = auth()->user();

    // Check roles and permissions for debugging
    $roles = $user->getRoleNames()->first();
    $permissions = $user->getAllPermissions()->pluck('name');

    // Debug response to see roles and permissions
    if (!$user->can('assign quizzes')) {
        return response()->json([
            'error' => 'Unauthorized',
            'roles' => $roles,
            'permissions' => $permissions
        ], 403);
    }



    $request->validate([
        'quiz_id' => 'required|exists:quizzes,id',
        'student_id' => 'required|exists:users,id'
    ]);


    if ($roles!=='Manager' && $roles!=='Admin') {
        return response()->json(['error' => 'You do not have permission to assign quizzes'], 403);
    }

    $student = User::find($request->student_id);
    if (!$student->hasRole('Student')) {
        return response()->json(['error' => 'The assigned user must be a student'], 400);
    }

    $assignedAt = Carbon::now();            
    $activateAt = $assignedAt->copy();
    // $activateAt = $assignedAt->copy()->addDays(2);
    $expiresAt = $activateAt->copy()->addDays(4);  
    

    $assignment = QuizAssignment::create([
        'quiz_id' => $request->quiz_id,
        'student_id' => $request->student_id,
        'assigned_by' => auth()->id(),  
        'assigned_at' => $assignedAt,
        'activate_at' => $activateAt,
        'expires_at' => $expiresAt,
    ]);

    return response()->json([
        'message' => 'Quiz assigned successfully!',
        'assignment' => $assignment
    ]);

}

public function attempt(Request $request, $quiz_id){
    $request->validate([
        'attempted_answers' => 'required|array',
        'attempted_answers.*.question_id' => 'required|exists:questions,id', 
        'attempted_answers.*.answer' => 'required|string',
    ]);

    $student = auth()->user();

    // Check if the quiz is assigned to the student
    $assignment = QuizAssignment::where('quiz_id', $quiz_id)
        ->where('student_id', $student->id)
        ->first();

    if (!$assignment) {
        return response()->json(['error' => 'Quiz not assigned to you.'], 403);
    }

    // Check if the quiz is within the activation period
    if ($assignment->activate_at > now() || $assignment->expires_at < now()) {
        return response()->json(['error' => 'This quiz is not currently available.'], 403);
    }

    $correctAnswers = 0;
    $totalQuestions = count($request->attempted_answers);

    // Logic for saving the student's answers
    foreach ($request->attempted_answers as $answer) {
        Answer::create([
            'quiz_assignment_id' => $assignment->id, 
            'question_id' => $answer['question_id'],
            'answer' => $answer['answer'],
        ]);


        // Fetch the correct option for the question from the quiz_options table
        $correctOption = QuizOption::where('question_id', $answer['question_id'])
        ->where('is_correct', 1)
        ->first();

        if (!$correctOption) {
            return response()->json(['error' => 'Correct option not found for question.'], 404);
        }

        // Compare the student's answer with the correct option's answer
        if ($answer['answer'] === $correctOption->option_text) {
            $correctAnswers++;
        }


}

    $assignment->update([
        'is_attempted' => true,
    ]);

    $scorePercentage = ($correctAnswers / $totalQuestions) * 100;


    return response()->json([
        'message' => 'Quiz attempted successfully!',
        'correct_answers' => $correctAnswers,
        'total_questions' => $totalQuestions,
        'percentage' => $scorePercentage,

    ]);
}

    public function checkStatus($quiz_id)
    {
        $assignment = QuizAssignment::where('quiz_id', $quiz_id)
            ->where('student_id', auth()->id())
            ->first();

        if (!$assignment) {
            return response()->json(['error' => 'Assignment not found'], 404);
        }

        $status = 'pending';
        // If Quiz assigned activate_at time is less than(lt) current time, status Inactive
        if (Carbon::now()->lt($assignment->activate_at)) {
            $status = 'not_active';

        // If current time is between Quiz assigned activate_at and  expiresAt time, status 'Active'
        } elseif (Carbon::now()->between($assignment->activate_at, $assignment->expires_at)) {
            $status = 'active';

        // If current time is greater than Quiz expiresAt time, status 'Expired'
        } elseif (Carbon::now()->gt($assignment->expires_at)) {
            $status = 'expired';
        }

        return response()->json(['status' => $status]);
    }




    public function uploadVideo(Request $request, $quiz_id){
    $request->validate([
        'student_id' => 'required|exists:users,id',
        'video' => 'required|mimes:mp4,avi,mov|max:204800', 
    ]);

    $student = auth()->user();
    $assignment = QuizAssignment::where('quiz_id', $quiz_id)
        ->where('student_id', $student->id)
        ->first();

    if (!$assignment) {
        return response()->json(['error' => 'Quiz not assigned to you.'], 403);
    }

    // Save the uploaded video to the storage folder
    if ($request->hasFile('video')) {
        $videoPath = $request->file('video')->store('quiz-videos', 'public');


        $assignment->update(['video_path' => $videoPath]);

        return response()->json(['message' => 'Video uploaded successfully!']);
    }

    return response()->json(['error' => 'Video upload failed.'], 400);
}

public function getAssignedQuizzes(){
    // Assuming you have a QuizAssignment model that tracks which quizzes are assigned to which student
    $assignedQuizzes = QuizAssignment::where('student_id', auth()->id())
        ->with('quiz') // Eager load quiz details
        ->get();

    return response()->json($assignedQuizzes);
}





}
