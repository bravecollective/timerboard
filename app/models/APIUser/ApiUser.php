<?php

use Illuminate\Auth\UserInterface;

class ApiUser extends Eloquent implements UserInterface {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'api_users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('token');

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $fillable = array('id', 'token', 'remember_token', 'character_name', 'alliance_id', 'alliance_name', 'alliance_ticker', 'tags', 'status', 'permission');

	/**
	 * Get the unique identifier for the user.
	 *
	 * @return mixed
	 */
	public function getAuthIdentifier()
	{
		return $this->getKey();
	}

	/**
	 * Get the password for the user.
	 *
	 * @return string
	 */
	public function getAuthPassword()
	{
		return $this->token;
	}

	public function getRememberToken()
	{
		return $this->remember_token;
	}

	public function setRememberToken($value)
	{
		$this->remember_token = $value;
	}

	public function getRememberTokenName()
	{
		return 'remember_token';
	}
        
        public function canViewDetails()
        {
                $detailsTags = array_merge(Config::get('braveapi.auth-fc-tags'), Config::get('braveapi.auth-titan-tags'));
                return $this->hasAnyTag($detailsTags) or $this->permission === '1';
        }
        
        public function isFC()
        {
                return $this->hasAnyTag(Config::get('braveapi.auth-fc-tags'));
        }
        
        public function isTitanPilot()
        {
                return $this->hasAnyTag(Config::get('braveapi.auth-titan-tags'));
        }
        
        public function hasTag($tag)
        {
                $tags = json_decode($this->tags);
                return in_array($tag, $tags);
        }
        
        public function hasAnyTag($tagList)
        {
                foreach($tagList as $tag){
                        if($this->hasTag($tag)){
                                return true;
                        }
                }
                return false;
        }
        
        public function getNameWithTicker()
        {
                if(empty($this->alliance_ticker))
                {
                        return $this->character_name;
                }
                return sprintf('[%s] %s', $this->alliance_ticker, $this->character_name);
        }

}