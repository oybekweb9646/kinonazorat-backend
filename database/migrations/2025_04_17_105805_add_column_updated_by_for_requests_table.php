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
        Schema::table('requests', function (Blueprint $table) {
            $table->integer('updated_by')->nullable();

            $table->foreign('updated_by')
                ->references('id')
                ->on('users');

            $table->index(['updated_by']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('requests', function (Blueprint $table) {
            $table->dropIndex(['updated_by']);
            $table->dropForeign(['updated_by']);
            $table->dropColumn('updated_by');

        });
    }
};
