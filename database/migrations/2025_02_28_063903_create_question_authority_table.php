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
        Schema::create('question_authority', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('authority_id');
            $table->bigInteger('stir')
                ->index()
                ->nullable();
            $table->integer('question_id');
        });

        Schema::table('question_authority', function (Blueprint $table) {
            $table->index('authority_id', 'question_authority-authority-index');
            $table->index('question_id', 'question_authority-question-index');

            $table->foreign('authority_id')->references('id')->on('authority');
            $table->foreign('question_id')->references('id')->on('questions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('question_authority', function (Blueprint $table) {
            $table->dropForeign('question_authority_authority_id_foreign');
            $table->dropForeign('question_authority_question_id_foreign');

            $table->dropIndex('question_authority-authority-index');
            $table->dropIndex('question_authority-question-index');
        });

        Schema::dropIfExists('question_authority');
    }
};
