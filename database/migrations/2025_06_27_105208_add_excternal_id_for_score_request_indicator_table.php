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
        Schema::table('score_request_indicator', function (Blueprint $table) {
            $table->string('externalId')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('score_request_indicator', function (Blueprint $table) {
            $table->dropColumn('externalId');
        });
    }
};
