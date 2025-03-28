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
        Schema::create('authority', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->text('name_uz');
            $table->text('name_ru')->nullable();
            $table->text('name_uzc')->nullable();
            $table->text('billing_address')->nullable();
            $table->text('director_address')->nullable();
            $table->integer('director_soato')->nullable();
            $table->text('address')->nullable();
            $table->integer('billing_soato')->nullable();
            $table->text('director_lastName')->nullable();
            $table->text('director_firstName')->nullable();
            $table->text('director_middleName')->nullable();
            $table->integer('director_gender')->nullable();
            $table->integer('director_nationality')->nullable();
            $table->integer('director_citizenship')->nullable();
            $table->integer('director_passportNumber')->nullable();
            $table->bigInteger('director_stir')->nullable();
            $table->bigInteger('director_pinfl')->nullable();
            $table->integer('director_countryCode')->nullable();
            $table->text('director_passportSeries')->nullable();
            $table->boolean('is_checked_checklist')->default(false);
            $table->boolean('is_checked_question')->default(false);
            $table->bigInteger('stir')
                ->index()
                ->unique();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('authority');
    }
};
