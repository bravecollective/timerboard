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
			$api = new Brave\API(Config::get('braveapi.application-endpoint'), Config::get('braveapi.application-identifier'), Config::get('braveapi.local-private-key'), Config::get('braveapi.remote-public-key'));
			$result = $api->core->info(array('token' => $credentials['token']));
			if(ApiUser::find($result->character->id) == false)
			{
				ApiUser::create(array(
					                'id' => $return->character->id,
					                'token' => $token,
					                'character_name' => $return->character->name,
					                'alliance_id' => $return->alliance->id,
					                'alliance_name' => $return->alliance->name,
					                'tags' => json_encode($return->tags),
					                'status' => 1,
					                'permission' => $permission
				                ));
			}
		}
		catch(Exception $e)
		{

		}
	}
}