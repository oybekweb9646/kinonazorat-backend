<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('score_request_indicator', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('request_id');
            $table->integer('indicator_id');
            $table->integer('score')->nullable();
            $table->integer('file_id')->nullable();
            $table->integer('file_name')->nullable();
            $table->integer('max_score');
        });

        Schema::table('score_request_indicator', function (Blueprint $table) {
            $table->index('request_id', 'idx-score_request_indicator-request_id');
            $table->foreign('request_id')->references('id')->on('requests')->cascadeOnDelete();

            $table->index('indicator_id', 'idx-score_request_indicator-indicator_id');
            $table->foreign('indicator_id')->references('id')->on('indicators')->cascadeOnDelete();

            $table->index('file_id', 'idx-score_request_indicator-file_id');
            $table->foreign('file_id')->references('id')->on('files')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('score_request_indicator', function (Blueprint $table) {
            $table->dropIndex('idx-score_request_indicator-file_id');
            $table->dropForeign('score_request_indicator_file_id_foreign');

            $table->dropIndex('idx-score_request_indicator-indicator_id');
            $table->dropForeign('score_request_indicator_indicator_id_foreign');

            $table->dropIndex('idx-score_request_indicator-request_id');
            $table->dropForeign('score_request_indicator_request_id_foreign');
        });

        Schema::dropIfExists('score_request_indicator');
    }
};
