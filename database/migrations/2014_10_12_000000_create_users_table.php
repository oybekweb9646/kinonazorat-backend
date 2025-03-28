<?php

use App\Core\Enums\Role\RoleEnum;
use App\Core\Enums\User\UserStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            $table->text('username')
                ->unique();

            $table->text('full_name')
                ->nullable();

            $table->bigInteger('pin_fl')
                ->index()
                ->unique()
                ->nullable();

            $table->bigInteger('stir')
                ->index()
                ->unique()
                ->nullable();

            $table->text('password')
                ->nullable();

            $table->text('auth_type')
                ->nullable()
                ->index();

            $table->integer('role')
                ->default(RoleEnum::_READ_ONLY->value)
                ->index();


            $table->text('egov_token')
                ->nullable()
                ->index();

            $table->date('date_of_birth')
                ->nullable();

            $table->text('phone')
                ->nullable();

            $table->text('position_name')
                ->nullable();

            $table->boolean('is_juridical')->default(false);

            $table->integer('authority_id')->nullable();

            $table->smallInteger('status')
                ->default(UserStatusEnum::_ACTIVE->value)
                ->index();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
