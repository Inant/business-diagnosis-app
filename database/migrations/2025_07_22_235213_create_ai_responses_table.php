<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ai_responses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_session_id');
            $table->string('step'); // diagnosis, swot, dst
            $table->longText('prompt');
            $table->longText('ai_response')->nullable();
            $table->json('ai_response_json')->nullable();
            $table->timestamps();

            $table->foreign('user_session_id')->references('id')->on('user_sessions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_responses');
    }
};
