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
        Schema::create('error_logs', function (Blueprint $table) {
            $table->id(); 

            $table->string('error_type');  // Error type (Exception, Fatal Error, etc.)
            $table->text('error_message');  // Error message
            $table->text('error_file');  // File where the error occurred
            $table->integer('error_line');  // Line number in the file
            $table->timestamp('error_time');  // Time of the error

            $table->timestamps();  // Created and updated timestamps
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('error_logs');
    }
};
