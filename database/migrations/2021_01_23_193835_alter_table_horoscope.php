<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableHoroscope extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('horoscope', function (Blueprint $table) {
            $table->boolean('is_send')->default(false);
            $table->timestamp('send_at')->nullable();
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
            $table->dropColumn(['is_send','send_at']);
        });
    }
}
