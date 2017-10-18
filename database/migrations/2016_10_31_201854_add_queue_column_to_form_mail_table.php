<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddQueueColumnToFormMailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('form_mail','queue')) {
            Schema::table('form_mail', function (Blueprint $table) {
                $table->boolean('queue');
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
        if (Schema::hasColumn('form_mail','queue')) {
            Schema::table('form_mail', function (Blueprint $table) {
                $table->dropColumn('queue');
            });
        }
    }
}
