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
        Schema::create('attempted_answers', function (Blueprint $table) {
            $table->id();

            $table->foreignId('quiz_assignment_id')->constrained('quiz_assignments')->onDelete('cascade');
            $table->foreignId('question_id')->constrained('questions')->onDelete('cascade'); // Assuming you have a questions table
            $table->string('answer');
            
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
        Schema::dropIfExists('answers');
    }
};
