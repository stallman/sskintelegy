<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToProductUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_user', function (Blueprint $table) {
            $table->unsignedBigInteger('bot_id')->nullable();
            $table->string('status')->nullable();
            $table->dateTime('possible_widthdraw_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropColumns('product_user', ['bot_id', 'status', 'possible_widthdraw_at']);
    }
}
