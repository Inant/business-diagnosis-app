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
        Schema::table('ai_responses', function (Blueprint $table) {
            $table->text('tujuan_pembuatan_konten')->nullable()->after('ai_response');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ai_responses', function (Blueprint $table) {
            $table->dropColumn('tujuan_pembuatan_konten');
        });
    }
};
