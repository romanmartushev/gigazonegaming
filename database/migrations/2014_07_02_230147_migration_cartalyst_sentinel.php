<?php

/**
 * Part of the Sentinel package.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the 3-clause BSD License.
 *
 * This source file is subject to the 3-clause BSD License that is
 * bundled with this package in the LICENSE file.
 *
 * @package    Sentinel
 * @version    2.0.12
 * @author     Cartalyst LLC
 * @license    BSD License (3-clause)
 * @copyright  (c) 2011-2015, Cartalyst LLC
 * @link       http://cartalyst.com
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class MigrationCartalystSentinel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::connection('mysql_sentinel')->hasTable('activations')) {
            Schema::connection('mysql_sentinel')->create('activations', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id')->unsigned();
                $table->string('code');
                $table->boolean('completed')->default(0);
                $table->timestamp('completed_at')->nullable();
                $table->timestamps();

                $table->engine = 'InnoDB';
            });
        }

        if (!Schema::connection('mysql_sentinel')->hasTable('persistences')) {
            Schema::connection('mysql_sentinel')->create('persistences', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id')->unsigned();
                $table->string('code');
                $table->timestamps();

                $table->engine = 'InnoDB';
                $table->unique('code');
            });
        }
        if (!Schema::connection('mysql_sentinel')->hasTable('reminders')) {
            Schema::connection('mysql_sentinel')->create('reminders', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id')->unsigned();
                $table->string('code');
                $table->boolean('completed')->default(0);
                $table->timestamp('completed_at')->nullable();
                $table->timestamps();
            });
        }
        if (!Schema::connection('mysql_sentinel')->hasTable('roles')) {
            Schema::connection('mysql_sentinel')->create('roles', function (Blueprint $table) {
                $table->increments('id');
                $table->string('slug');
                $table->string('name');
                $table->text('permissions')->nullable();
                $table->timestamps();

                $table->engine = 'InnoDB';
                $table->unique('slug');
            });
        }
        if (!Schema::connection('mysql_sentinel')->hasTable('role_users')) {
            Schema::connection('mysql_sentinel')->create('role_users', function (Blueprint $table) {
                $table->integer('user_id')->unsigned();
                $table->integer('role_id')->unsigned();
                $table->nullableTimestamps();

                $table->engine = 'InnoDB';
                $table->primary(['user_id', 'role_id']);
            });
        }
        if (!Schema::connection('mysql_sentinel')->hasTable('throttle')) {
            Schema::connection('mysql_sentinel')->create('throttle', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id')->unsigned()->nullable();
                $table->string('type');
                $table->string('ip')->nullable();
                $table->timestamps();

                $table->engine = 'InnoDB';
                $table->index('user_id');
            });
        }
        if (!Schema::connection('mysql_sentinel')->hasTable('users')) {
            Schema::connection('mysql_sentinel')->create('users', function (Blueprint $table) {
                $table->increments('id');
                $table->string('email');
                $table->string('password');
                $table->text('permissions')->nullable();
                $table->timestamp('last_login')->nullable();
                $table->timestamps();

                $table->engine = 'InnoDB';
                $table->unique('email');
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
        if (Schema::connection('mysql_sentinel')->hasTable('activations')) {
            Schema::connection('mysql_sentinel')->drop('activations');
        }
        if (Schema::connection('mysql_sentinel')->hasTable('persistences')) {
            Schema::connection('mysql_sentinel')->drop('persistences');
        }
        if (Schema::connection('mysql_sentinel')->hasTable('reminders')) {
            Schema::connection('mysql_sentinel')->drop('reminders');
        }
        if (Schema::connection('mysql_sentinel')->hasTable('roles')) {
            Schema::connection('mysql_sentinel')->drop('roles');
        }
        if (Schema::connection('mysql_sentinel')->hasTable('role_users')) {
            Schema::connection('mysql_sentinel')->drop('role_users');
        }
        if (Schema::connection('mysql_sentinel')->hasTable('throttle')) {
            Schema::connection('mysql_sentinel')->drop('throttle');
        }
        if (Schema::connection('mysql_sentinel')->hasTable('users')) {
            Schema::connection('mysql_sentinel')->drop('users');
        }
    }
}
