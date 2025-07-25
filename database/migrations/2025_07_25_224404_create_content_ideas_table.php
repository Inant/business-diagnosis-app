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
        Schema::create('content_ideas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_session_id');
            $table->integer('hari_ke'); // Nomor urut hari
            $table->string('judul_konten');
            $table->string('pilar_konten')->nullable();
            $table->string('hook')->nullable();
            $table->json('script_poin_utama')->nullable();
            $table->string('call_to_action')->nullable();
            $table->string('rekomendasi_format')->nullable();
            $table->timestamps();

            $table->foreign('user_session_id')->references('id')->on('user_sessions')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_ideas');
    }
};
