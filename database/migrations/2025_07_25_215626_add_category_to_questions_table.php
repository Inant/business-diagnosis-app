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
        Schema::table('questions', function (Blueprint $table) {
            $table->string('category', 100)->after('slug')->default(1)->nullable()->comment('1 untuk 17 pertanyaan; 2 untuk yang 8 pertanyaan');
            // Ganti 'Umum' jika ingin default lain atau bisa null jika tidak wajib
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }
};
