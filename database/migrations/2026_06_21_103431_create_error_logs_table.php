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
        Schema::create('error_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('username')->nullable();
            $table->integer('user_role')->nullable();
            $table->string('method', 10)->nullable();
            $table->string('url')->nullable();
            $table->text('message');
            $table->string('exception_class')->nullable();
            $table->text('file')->nullable();
            $table->integer('line')->nullable();
            $table->text('trace')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->integer('status_code')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('error_logs');
    }
};
