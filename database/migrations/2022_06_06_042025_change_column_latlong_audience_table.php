<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnLatlongAudienceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('audiences', function (Blueprint $table) {
            $table->float('latitude', 18,6)->nullable()->change();
            $table->float('longitude', 18,6)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('audiences', function (Blueprint $table) {
            $table->float('latitude')->nullable()->change();
            $table->float('longitude')->nullable()->change();
        });
    }
}
