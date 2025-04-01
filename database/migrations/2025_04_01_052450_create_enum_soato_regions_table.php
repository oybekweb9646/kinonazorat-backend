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
        Schema::create('enum_soato_regions', function (Blueprint $table) {
            $table->id();
            $table->string('name_uzc');
            $table->string('name_uz');
            $table->string('name_ru');
            $table->integer('parent_id')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->integer('region_id')->nullable();
            $table->foreign('region_id')
                ->references('id')
                ->on('enum_soato_regions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('users_region_id_foreign');
            $table->dropColumn('region_id');
        });

        Schema::dropIfExists('enum_soato_regions');
    }
};
