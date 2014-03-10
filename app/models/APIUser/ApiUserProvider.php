<?php

use Illuminate\Auth\UserProviderInterface;

class ApiUserProvider implements UserProviderInterface {

	public function retrieveById($identifier)
	{
		return ApiUser::find($identifier);
	}

	public function retrieveByCredentials(array $credentials)
	{
		$user = ApiUser::where('token', '=', $credentials['token'])->get();
		return $user[0];
	}

	public function validateCredentials(ApiUser $user, array $credentials)
	{
		if($user->token == $credentials['token'])
		{
			return true;
		}

		try
		{
			$api = Brave\API(Config::get('braveapi.application-identifier'), Config::get('braveapi.local-private-key'), Config::get('braveapi.remote-public-key'));
			$result = $api->core->info(array('token' => $credentials['token']));
			var_export($result);
			exit;
		}
		catch(Exception $e)
		{

		}
	}
}