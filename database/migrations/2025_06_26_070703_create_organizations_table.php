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
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->string('name_uz')->nullable();
            $table->string('name_ru')->nullable();
            $table->string('name_uzc')->nullable();
            $table->string('inn')->nullable();
            $table->integer('region_id')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('region_id')
                ->references('id')
                ->on('enum_soato_regions');

            $table->foreign('created_by')
                ->references('id')
                ->on('users');

            $table->foreign('updated_by')
                ->references('id')
                ->on('users');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('users_region_id_foreign');
            $table->dropColumn('region_id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->integer('organization_id')->nullable();
            $table->foreign('organization_id')
                ->references('id')
                ->on('organizations');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('users_organization_id_foreign');
            $table->dropColumn('organization_id');

            $table->integer('region_id')->nullable();
            $table->foreign('region_id')
                ->references('id')
                ->on('enum_soato_regions');
        });

        Schema::table('organizations', function (Blueprint $table) {
            $table->dropForeign('organizations_created_by_foreign');
            $table->dropColumn('created_by');

            $table->dropForeign('organizations_updated_by_foreign');
            $table->dropColumn('updated_by');
        });

        Schema::dropIfExists('organizations');
    }
};
