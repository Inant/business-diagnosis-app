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
        Schema::create('shooting_scripts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('content_idea_id');
            $table->unsignedBigInteger('user_session_id');
            $table->string('gaya_pembawaan');
            $table->integer('target_durasi');
            $table->string('penyebutan_audiens');
            $table->text('script_json'); // hasil JSON script
            $table->text('raw_ai_response')->nullable(); // jika ingin simpan raw
            $table->timestamps();

            $table->foreign('content_idea_id')->references('id')->on('content_ideas')->onDelete('cascade');
            $table->foreign('user_session_id')->references('id')->on('user_sessions')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shooting_scripts');
    }
};
