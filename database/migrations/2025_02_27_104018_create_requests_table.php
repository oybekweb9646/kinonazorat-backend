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
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('authority_id');
            $table->text('request_no');
            $table->date('closed_at')->nullable();
            $table->integer('status')->default(1);
            $table->integer('score')->nullable();
            $table->integer('indicator_type_id');
            $table->integer('created_by');
            $table->integer('year')->nullable();
            $table->integer('quarter')->nullable();
            $table->integer('month')->nullable();
            $table->date('registered_date')->nullable();
            $table->bigInteger('stir')
                ->index()
                ->nullable();
        });

        Schema::table('requests', function (Blueprint $table) {
            $table->foreign('authority_id')->references('id')->on('authority');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
};
