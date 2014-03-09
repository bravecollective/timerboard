<?php

use Illuminate\Auth\UserProviderInterface;

class ApiUserProvider implements UserProviderInterface {

	public function retrieveById($identifier)
	{

	}

	public function retrieveByCredentials(array $credentials)
	{

	}

	public function validateCredentials(ApiUserProvider $user, array $credentials)
	{

	}
}