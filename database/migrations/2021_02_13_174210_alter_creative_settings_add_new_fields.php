<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCreativeSettingsAddNewFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('creative_settings', function (Blueprint $table) {
            $table->string('object_getter_class');
            $table->string('generator_class');

            $table->dropColumn('type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('creative_settings', function (Blueprint $table) {
            $table->string('type');

            $table->dropColumn(['object_getter_class', 'generator_class']);
        });
    }
}
