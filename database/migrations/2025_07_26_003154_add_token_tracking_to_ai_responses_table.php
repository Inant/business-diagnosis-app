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
            $table->integer('tokens_used')->nullable()->after('ai_response');
            $table->decimal('cost_idr', 10, 2)->nullable()->after('tokens_used');
            $table->integer('response_time_ms')->nullable()->after('cost_idr');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ai_responses', function (Blueprint $table) {
            $table->dropColumn(['tokens_used', 'cost_idr', 'response_time_ms']);
        });
    }
};
