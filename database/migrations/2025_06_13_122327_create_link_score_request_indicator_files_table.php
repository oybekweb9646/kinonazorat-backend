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
        Schema::create('link_score_request_indicator_files', function (Blueprint $table) {
            $table->id();
            $table->integer('score_request_indicator_id');
            $table->integer('file_id');
            $table->timestamps();

            $table->index('score_request_indicator_id', 'idx-link_score_request_indicator_files-score_request_indicator_id');
            $table->index('file_id', 'idx-link_score_request_indicator_files-file_id');

            $table->foreign('score_request_indicator_id')->references('id')->on('score_request_indicator')->restrictOnDelete();
            $table->foreign('file_id')->references('id')->on('files')->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('link_score_request_indicator_files');
    }
};
