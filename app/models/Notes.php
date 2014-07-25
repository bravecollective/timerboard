<?php

/**
 * Class Notes
 *
 * Notes on a Timer
 */
class Notes extends Eloquent{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'notes';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array();

	/**
	 * The attributes that can be edited in models.
	 *
	 * @var array
	 */
	protected $fillable = array(
		'content',
		'timer'
	);
        
        public function user()
        {
                return $this->belongsTo('ApiUser');
        }
}