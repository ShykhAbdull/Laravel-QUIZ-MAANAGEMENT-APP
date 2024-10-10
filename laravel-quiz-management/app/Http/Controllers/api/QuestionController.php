<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\QuizOption;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function create(Request $request, $quiz_id)
{
    $request->validate([
        'question_text' => 'required|string',
        'question_type' => 'required|string', 
        'options' => 'required|array|min:2', 
        'options.*' => 'required|string',    
        'correct_answer' => 'required|string', 
    ]);

    $quiz = Quiz::findOrFail($quiz_id);

    $question = Question::create([
        'quiz_id' => $quiz->id,
        'question_text' => $request->question_text,
        'question_type' => $request->question_type,
    ]);

    foreach ($request->options as $option) {
        $is_correct = ($option === $request->correct_answer);

        QuizOption::create([
            'quiz_id' => $quiz->id,
            'question_id' => $question->id,
            'option_text' => $option,
            'is_correct' => $is_correct,
        ]);
    }

    return response()->json([
        'success' => 'Sucess',
        'message' => 'Question and options added successfully',
    ], 201);
}

}
