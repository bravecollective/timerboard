<?php
class TimersTableSeeder extends Seeder {

	public function run()
	{
		DB::table('timers')->delete();

		Timers::create(array(
			'itemID' => '40228414', //Ogaria I -> Dec 18 1130 UTC
			'bashed' => 0,
			'outcome' => 0,
			'structureType' => 0,
			'timerType' => 0,
			'timeExiting' => date('Y-m-d H:i:s', 1387366200),
			'user_id' => '1',
			'type' => '0',
		));
	}
}