<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizAssignment extends Model
{
    use HasFactory;

    protected $table = 'quiz_assignments';

    protected $fillable = [
        'quiz_id', 
        'student_id',  
        'assigned_by', 
        'assigned_at', 
        'activate_at', 
        'expires_at', 
        'is_attempted'
    ];

        // Define the relationships
        public function quiz()
        {
            return $this->belongsTo(Quiz::class);
        }
    
        public function student()
        {
            return $this->belongsTo(User::class, 'student_id');
        }
    
        public function manager()
        {
            return $this->belongsTo(User::class, 'assigned_by');
        }


}
