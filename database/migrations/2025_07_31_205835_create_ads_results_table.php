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
        Schema::create('ads_results', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('user_session_id');
            $table->enum('platform', ['facebook_instagram', 'tiktok', 'google_search']);
            $table->string('goal');
            $table->string('product');
            $table->string('offer')->nullable();
            $table->text('prompt');               // Tambahan: Simpan prompt ke AI
            $table->text('ai_response');          // Simpan respons AI (teks)
            $table->integer('tokens_used')->nullable();        // Jumlah token
            $table->decimal('cost_idr', 10, 2)->nullable();           // Biaya (Rp)
            $table->integer('response_time_ms')->nullable();   // Lama response AI (ms)
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('user_session_id')->references('id')->on('user_sessions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ads_results');
    }
};
