<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('integration_logs', function (Blueprint $table) {
            $table->id();
            $table->string('method', 10)->nullable();
            $table->text('path')->nullable();
            $table->string('client_ip', 64)->nullable();
            $table->boolean('token_valid')->default(false);
            $table->boolean('allowed')->default(false);
            $table->string('reason', 64)->nullable(); // ok | invalid_token | ip_denied | not_configured
            $table->integer('status_code')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('created_at')->nullable()->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('integration_logs');
    }
};
