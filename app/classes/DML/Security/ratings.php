<?php

namespace DML\Security;

Class Ratings {

	protected $colors = array(
		'1.0' => '2FEFEF',
		'0.9' => '48F0C0',
		'0.8' => '00EF47',
		'0.7' => '00F000',
		'0.6' => '8FEF2F',
		'0.5' => 'EFEF00',
		'0.4' => 'D77700',
		'0.3' => 'F06000',
		'0.2' => 'F04800',
		'0.1' => 'D73000',
		'0.0' => 'F00000',
	);

	function getColorCode($rating)
	{
		if(is_float($rating))
		{
			return $this->colors[(string)$rating];
		}
		else
		{
			return false;
		}
	}
}