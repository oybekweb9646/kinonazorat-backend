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
        Schema::table('log', function (Blueprint $table) {
            $table->string('request_id')->nullable()->after('id');
            $table->string('score_request_indicator_id')->nullable()->after('id');
            $table->string('authority_id')->nullable()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('log', function (Blueprint $table) {
            $table->dropColumn('request_id');
            $table->dropColumn('score_request_indicator_id');
            $table->dropColumn('authority_id');
        });
    }
};
