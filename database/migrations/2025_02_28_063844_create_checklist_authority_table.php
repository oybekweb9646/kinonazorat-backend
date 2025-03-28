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
        Schema::create('checklist_authority', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('authority_id');
            $table->bigInteger('stir')
                ->index()
                ->nullable();
            $table->integer('checklist_id');
            $table->boolean('is_checked');
        });

        Schema::table('checklist_authority', function (Blueprint $table) {
            $table->index('checklist_id', 'idx-checklist_authority-checklist_id');
            $table->index('authority_id', 'idx-checklist_authority-authority_id');

            $table->foreign('checklist_id')->references('id')->on('checklists')->onDelete('cascade');
            $table->foreign('authority_id')->references('id')->on('authority')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('checklist_authority', function (Blueprint $table) {
            $table->dropIndex('idx-checklist_authority-checklist_id');
            $table->dropIndex('idx-checklist_authority-authority_id');

            $table->dropForeign('checklist_authority_checklist_id_foreign');
            $table->dropForeign('checklist_authority_authority_id_foreign');
        });
        Schema::dropIfExists('checklist_authority');
    }
};
