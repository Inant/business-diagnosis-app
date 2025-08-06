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
        Schema::create('ads_plans', function (Blueprint $table) {
            $table->id();
            $table->string('ads_plan_id')->unique(); // ap_timestamp_sessionid
            $table->foreignId('user_session_id')->constrained('user_sessions')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('platform', ['facebook_instagram', 'tiktok', 'google_search']);
            $table->string('goal');
            $table->string('product');
            $table->text('offer')->nullable();
            $table->longText('prompt');
            $table->longText('ai_response');
            $table->integer('tokens_used')->default(0);
            $table->decimal('cost_idr', 10, 2)->default(0);
            $table->integer('response_time_ms')->default(0);
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
            $table->index('ads_plan_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ads_plans');
    }
};
