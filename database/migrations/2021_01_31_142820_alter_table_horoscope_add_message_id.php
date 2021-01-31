<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableHoroscopeAddMessageId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('horoscope', function (Blueprint $table) {
            $table->integer('message_id')->nullable();
            $table->dropColumn('is_send');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('horoscope', function (Blueprint $table) {
            $table->dropColumn('message_id');
            $table->boolean('is_send')->default(false);
        });
    }
}
