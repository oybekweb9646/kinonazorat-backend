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
        Schema::table('requests', function (Blueprint $table) {
            $table->string('order_number')->nullable();
            $table->date('order_date')->nullable();
            $table->string('order_inspector')->nullable();
            $table->integer('order_file_id')->nullable();
            $table->string('act_number')->nullable();
            $table->date('act_date')->nullable();
            $table->integer('act_file_id')->nullable();

            $table->foreign('order_file_id')
                ->references('id')
                ->on('files');

            $table->foreign('act_file_id')
                ->references('id')
                ->on('files');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('requests', function (Blueprint $table) {
            $table->dropColumn('order_number');
            $table->dropColumn('order_date');
            $table->dropColumn('order_inspector');
            $table->dropColumn('act_number');
            $table->dropColumn('act_date');

            $table->dropForeign('requests_order_file_id_foreign');
            $table->dropColumn('order_file_id');

            $table->dropForeign('requests_act_file_id_foreign');
            $table->dropColumn('act_file_id');
        });
    }
};
