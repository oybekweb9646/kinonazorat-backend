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
        Schema::create('indicators', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->text('name_uz');
            $table->text('name_ru')->nullable();
            $table->text('name_uzc')->nullable();
            $table->integer('type_id');
            $table->boolean('is_active')->default(true);
            $table->integer('max_score')->nullable();
        });

        Schema::table('indicators', function (Blueprint $table) {
            $table->index('type_id', 'idx-indicators-type_id');

            $table->foreign('type_id')->references('id')->on('indicator_types')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('indicators', function (Blueprint $table) {
            $table->dropIndex('idx-indicators-type_id');

            $table->dropForeign('indicators_type_id_foreign');
        });

        Schema::dropIfExists('indicators');
    }
};
