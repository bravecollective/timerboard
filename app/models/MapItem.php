<?php

class MapItem extends Eloquent{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'mapDenormalize';

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
	protected $primaryKey = 'itemID';

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