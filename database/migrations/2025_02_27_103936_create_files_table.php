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
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->text('name')->unique();
            $table->text('original_name');
            $table->text('mime_type');
            $table->text('path');
            $table->text('disk')->default('local');
            $table->text('hash', 64)->unique();
            $table->text('collection')->nullable();
            $table->unsignedBigInteger('size');
        });

        Schema::table('files', function (Blueprint $table) {
            $table->index('hash', 'idx-files-file_hash');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('files', function (Blueprint $table) {
            $table->dropIndex('idx-files-file_hash');
        });

        Schema::dropIfExists('files');
    }
};
