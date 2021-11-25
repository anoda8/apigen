<?php

use App\Models\Events;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAudiencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('audiences', function (Blueprint $table) {
            $table->id();
            $table->string('photoUrl')->nullable();
            $table->dateTime('entry_date');
            $table->float('latitude')->nullable();
            $table->float('longitude')->nullable();
            $table->string('message')->nullable();
            $table->string('token');
            $table->boolean('saved')->nullable();
            $table->foreignIdFor(User::class);
            $table->foreignIdFor(Events::class);
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
        Schema::dropIfExists('audiences');
    }
}
