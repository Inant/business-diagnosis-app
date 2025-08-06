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
        Schema::table('content_ideas', function (Blueprint $table) {
            $table->string('content_plan_id')->nullable()->after('user_session_id');
            $table->index('content_plan_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('content_ideas', function (Blueprint $table) {
            $table->dropIndex(['content_plan_id']);
            $table->dropColumn('content_plan_id');
        });
    }
};
