<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSignUpTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('timer_sign_ups', function($table)
		{
			$table->primary(array('api_user_id', 'timers_id', 'role'));
                        $table->integer('api_user_id');
                        $table->integer('timers_id');
                        $table->integer('role');
                        $table->boolean('confirmed');
		});
                
                Schema::create('notes', function($table)
                {
                        $table->text('content');
                        $table->integer('timers_id');
                        $table->integer('user_id')->unsigned();
                        $table->foreign('user_id')->references('id')->on('api_users');
                        $table->timestamps();
                });
                
                Schema::table('api_users', function($table)
                {
                        $table->string('alliance_ticker', 5);
                });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('timer_sign_ups');
                
                Schema::drop('notes');
                
                Schema::table('api_users', function($table)
                {
                        $table->dropColumn('alliance_ticker');
                });
	}

}
