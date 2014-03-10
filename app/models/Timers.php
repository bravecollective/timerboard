<?php

class Timers extends Eloquent{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'timers';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array();

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	public static $POCOGroupID = 7;

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $fillable = array(
		'itemID',
		'structureType',
		'structureStatus',
		'bashed',
		'outcome',
		'timerType',
		'timeExiting',
		'user_id'
	);

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	public static $structureIDs = array(
		'1' => 'POS',
		'2' => 'POCO',
		'3' => 'Station',
		'4' => 'I-Hub',
		'5' => 'TCU'
	);

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	public static $structureStatus = array(
		'0' => '',
		'1' => 'Shield',
		'2' => 'Armor',
	);

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	public static $timerType = array(
		'0' => 'Offensive',
		'1' => 'Defensive'
	);

}