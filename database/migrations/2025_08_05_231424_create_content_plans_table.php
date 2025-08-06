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
        Schema::create('content_plans', function (Blueprint $table) {
            $table->id();
            $table->string('content_plan_id')->unique(); // cp_timestamp_sessionid
            $table->foreignId('user_session_id')->constrained('user_sessions')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->integer('days'); // durasi konten (7, 14, 21, 30)
            $table->text('tujuan_pembuatan_konten')->nullable();
            $table->longText('prompt'); // prompt yang dikirim ke AI
            $table->longText('ai_response'); // response JSON dari AI
            $table->integer('tokens_used')->default(0);
            $table->decimal('cost_idr', 10, 2)->default(0);
            $table->integer('response_time_ms')->default(0);
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
            $table->index('content_plan_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_plans');
    }
};
