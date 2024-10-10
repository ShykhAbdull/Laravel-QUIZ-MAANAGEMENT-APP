<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quiz_assignments', function (Blueprint $table) {
            $table->id();
    
            // Foreign key for the quiz being assigned
            $table->foreignId('quiz_id')->constrained()->onDelete('cascade');
    
            // Foreign key for the student (student_id)
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade'); 
    
            // Foreign key for the manager or admin assigning the quiz
            $table->foreignId('assigned_by')->constrained('users')->onDelete('cascade'); 
    
            // Timestamps for assignment, activation, and expiration
            $table->timestamp('assigned_at');         
            $table->timestamp('activate_at')->nullable(); 
            $table->timestamp('expires_at')->nullable();   
    
            // Quiz attempted status
            $table->boolean('is_attempted')->default(false); 
    
            // Created and updated timestamps
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quiz_assignments');
    }
};
