<?php

class MapConstellation extends Eloquent{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'mapconstellations';

	/**
	 * The database connection used by the model.
	 *
	 * @var string
	 */
	protected $connection = 'eve_data';

	/**
	 * The database column primary key.
	 *
	 * @var string
	 */
	protected $primaryKey = 'constellationID';

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
	protected $fillable = array();

}