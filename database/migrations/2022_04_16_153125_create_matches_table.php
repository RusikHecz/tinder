<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('matches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('target_user_id');
            $table->timestamps();

            $table->index('user_id', 'matches_user_idx');
            $table->index('target_user_id', 'matches_target_user_idx');

            $table->foreign('user_id', 'matches_user_fk')->on('users')->references('id');
            $table->foreign('target_user_id', 'matches_target_user_fk')->on('users')->references('id');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('matches');
    }
}
