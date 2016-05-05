<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyTimersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

        Schema::table('timers', function($table)
        {
            $table->enum('priority', ['1', '2', '3', '4', '5'])->default('3');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{

        Schema::table('timers', function($table)
        {
            $table->dropColumn('priority');
        });
	}

}
