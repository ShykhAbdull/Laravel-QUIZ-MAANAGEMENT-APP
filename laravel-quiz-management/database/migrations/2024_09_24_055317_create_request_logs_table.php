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
        Schema::create('request_logs', function (Blueprint $table) {
            $table->id();  // Primary Key
            
            $table->string('request_method');  // HTTP method (GET, POST, etc.)
            $table->string('request_url');  // Request URL
            $table->string('user_agent')->nullable();  // User agent, nullable
            $table->string('ip_address');  // Client IP address
            $table->json('request_body')->nullable();  
            $table->timestamp('request_time');  // Time of the request
            
            $table->text('response_body')->nullable();  
            $table->integer('response_status_code')->nullable();  

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
        Schema::dropIfExists('request_logs');
    }
};
