<?php

class MapRegion extends Eloquent{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'mapregions';

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
	protected $primaryKey = 'regionID';

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