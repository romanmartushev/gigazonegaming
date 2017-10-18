<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddTournamentOpenAndCloseTimes extends Migration
{
    protected $columns = ['sign_up_open', 'sign_up_close'];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::connection('mysql_champ')->hasColumns('tournaments', $this->columns)) {
            Schema::connection('mysql_champ')->table('tournaments', function (Blueprint $table) {
                for ($i = 0; $i < count($this->columns); $i++) {
                    $table->dateTime($this->columns[$i]);
                }
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
        if (Schema::connection('mysql_champ')->hasColumns('tournaments', $this->columns)) {
            for ($i = 0; $i < count($this->columns); $i++) {
                if (Schema::connection('mysql_champ')->hasColumn('tournaments', $this->columns[$i])) {
                    Schema::connection('mysql_champ')->table('tournaments', function (Blueprint $table) use ($i) {
                        $table->dropColumn($this->columns[$i]);
                    });
                }
            }
        }
    }
}
