<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BigAlterHoroscopeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('horoscope', function (Blueprint $table) {
            $table->dropColumn(['send_at', 'message_id', 'horoscope_setting_id', 'short_description']);

            $table->string('zodiac_name')->nullable();
            $table->string('description_parse_url', 500)->nullable();

            $table->mediumText('description')->nullable()->change();
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
            $table->timestamp('send_at')->nullable();
            $table->integer('message_id')->nullable();
            $table->integer('horoscope_setting_id');
            $table->string('short_description', 500)->nullable();

            $table->dropColumn(['zodiac_name', 'description_parse_url']);
        });
    }
}
