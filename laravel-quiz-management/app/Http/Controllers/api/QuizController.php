<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function create(Request $request){


        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $quiz = Quiz::create([
            'title' => $request->title,
            'description' => $request->description,
        ]);
    
        return response()->json([
            'success' => 'Success',
            'message' => 'Quiz created successfully',
            'quiz_id' => $quiz->id
        ], 201);



    }
}
