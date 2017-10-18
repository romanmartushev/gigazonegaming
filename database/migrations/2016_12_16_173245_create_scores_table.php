<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::connection('mysql_champ')->hasTable('scores')) {
            Schema::connection('mysql_champ')->create('scores', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('player');
                $table->integer('tournament');
                $table->string('score');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(Schema::connection('mysql_champ')->hasTable('scores')) {
            Schema::connection('mysql_champ')->drop('scores');
        }
    }
}
