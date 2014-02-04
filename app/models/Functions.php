<?php

class Functions extends Eloquent{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'pos_functions';

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
	protected $fillable = array(
		'itemID',
		'functionID'
	);

	/**
	 * The possible functions a POC can preform
	 *
	 * @var array
	 */
	public static $functionTypes = array(
		'0' => 'Unknown',
		'1' => 'T1 Manufacturing',
		'2' => 'T2 Manufacturing',
		'3' => 'T3 Manufacturing',
		'4' => 'Capital Manufacturing',
		'5' => 'Moon Mining',
		'6' => 'Moon Reactions',
		'7' => 'Drug Lab',
		'8' => 'Research Lab',
		'9' => 'Jump Bridge',
		'10' => 'Staging',
		'11' => 'Cyno Jammer',
		'12' => 'Shield Hardened',
		'13' => 'Refining',
		'14' => 'Ship Fitting',
		'15' => 'Item Storage',
		'16' => 'CSAA',
		'17' => 'Deathstar',
		'18' => 'ECMStar',
		'19' => 'Titan Staging',
	);

	public function pos()
	{
		return $this->belongsTo('Pos', 'itemID');
	}
}