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
        Schema::create('api_usages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('session_id')->nullable();
            $table->string('step')->nullable(); // diagnosis, swot, content_plan, shooting_script
            $table->integer('prompt_length');
            $table->integer('response_length');
            $table->integer('input_tokens');
            $table->integer('output_tokens');
            $table->integer('total_tokens');
            $table->decimal('input_cost_idr', 10, 2);
            $table->decimal('output_cost_idr', 10, 2);
            $table->decimal('total_cost_idr', 10, 2);
            $table->integer('response_time_ms');
            $table->string('model');
            $table->string('status'); // success, error
            $table->text('error_message')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
            $table->index(['session_id']);
            $table->index(['step']);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_usages');
    }
};
