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
        Schema::create('app_error_logs', function (Blueprint $table) {
            $table->id();
            $table->string('level')->nullable();
            $table->text('message')->nullable();
            $table->text('context')->nullable();
            $table->text('url')->nullable();
            $table->string('ip')->nullable();
            $table->integer('user_id')->nullable();
            $table->text('body_params')->nullable();
            $table->text('query_params')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_error_logs');
    }
};
