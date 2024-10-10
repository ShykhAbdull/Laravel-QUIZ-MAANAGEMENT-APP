<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;



    protected $table = 'attempted_answers';


    protected $fillable = [
        'quiz_assignment_id',
        'question_id',
        'answer',
    ];

    // Define the relationship to the QuizAssignment
    public function quizAssignment()
    {
        return $this->belongsTo(QuizAssignment::class);
    }

    // Define the relationship to the Question
    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
